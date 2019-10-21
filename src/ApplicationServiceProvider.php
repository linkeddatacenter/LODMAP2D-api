<?php
/*
 * This file is part of the LODMAP2D API project.
 *
 * (c) Enrico Fagnoni <enrico@linkeddata.center>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LODMAP2D;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use uSilex\Api\BootableProviderInterface;

class ApplicationServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['backend'] = 'http://sdaas:8080/sdaas/sparql'; // Override with LODMAP2D_BACKEND env variable
        $app['CORS.AllowedOrigins'] = '*';                  // Override with LODMAP2D_CORS_ALLOWEDORIGINS env variable
        $app['cache.expire'] = '+1 hour';                   // Override with LODMAP2D_CACHE_EXPIRE env variable
        
       
        /*
         * Define PSR Implementations and Middleware Pipeline
         */
        $app->register(new \uSilex\Provider\Psr7\GuzzleServiceProvider());
        $app->register(new \uSilex\Provider\Psr15\RelayServiceProvider());
        $app['handler.queue'] = [
            'contentNegotiation',   // manage content negotiation
            'urlRewriter',          // rewrite request path to remove extensions
            'cors',                 // Cross-Origin Resource Sharing (CORS)
            'gzipEncoder',          // Compress the response to gzip
            'cache',                // Add cache expiration headers
            'fastRoute',            // Handle the routes with fast-route
            'requestHandler',       // Handle the route
        ];
        
        
        
        // Select httpClient implementation
        $app['store'] = function ($app) {
            return new \GuzzleHttp\Client([
                'base_uri' => $app['backend']
            ]);
        };
        
        
        $app['controller'] = function ($app) {
            return new Controller($app);
        };
        
        
        /****************************************
         *             MIDDLEWARES
         ****************************************/
        
        // manage content negotiation
        $app['contentNegotiation'] = function () {
            return new \Middlewares\ContentType([
                'turtle' => [
                    'extension' => ['ttl', 'turtle', 'n3', 'txt'],
                    'mime-type' => ['text/turtle', 'application/x-turtle', 'text/rdf+n3'],
                    'charset' => true,
                ],
                'n-triples' => [
                    'extension' => ['nt', 'ntriples'],
                    'mime-type' => ['text/plain', 'application/n-triples', 'application/x-n-triples-RDR' ],
                    'charset' => true,
                ],
                'rdf+xml' => [
                    'extension' => ['rdf', 'xml','rdfs', 'owl' ],
                    'mime-type' => ['application/rdf+xml','text/xml', 'application/xml' ],
                    'charset' => true,
                ],
                'jsonld' => [
                    'extension' => ['jsonld', 'json'],
                    'mime-type' => ['application/ld+json'],
                    'charset' => true,
                ]
            ]);
        };
        
        
        // remove extension form url
        $app['urlRewriter'] = function ($app) {
            return new \LinkedDataCenter\UrlRewriter([
                '/$' => '/app',             # root defaults to /app
                '/(.*)\\.(ttl|turtle|n3|txt|nt|ntriples|rdf|xml|rdfs|owl|jsonld|json)$' => '/$1',    # remove known extensions
            ]);
        };
        
        //Cross-Origin Resource Sharing (CORS)
        $app['cors'] = function ($app) {
            $settings = (new \Neomerx\Cors\Strategies\Settings())
                ->setRequestAllowedOrigins([$app['CORS.AllowedOrigins']=>true])
                ->setRequestAllowedMethods(['GET'=>true])
                ->setRequestAllowedHeaders(['accept'=>true])
                ->setForceAddAllowedHeadersToPreFlightResponse(true)
                ->setRequestCredentialsSupported(true)
            ;
            $analyzer = \Neomerx\Cors\Analyzer::instance($settings);
            return new \Middlewares\Cors($analyzer);
        };
        
        
        //Compress the response to gzip
        $app['gzipEncoder'] = function () {
            return new \Middlewares\GzipEncoder();
        };
        
        
        //Add cache expiration headers
        $app['cache'] = function ($app) {
            return (new \Middlewares\Expires())
                ->defaultExpires($app['cache.expire'])
            ;
        };


        // Routes http requests
        $app['fastRoute'] = function () {          
            $dispatcher = \FastRoute\simpleDispatcher(function (\FastRoute\RouteCollector $r) {        
                $singleAction = 'app|credits|terms|table-view|overview|partitions|accounts-index|account-view|bgo';
                $actionOnId = 'account';
                $r->addRoute('GET', "/{resource:$singleAction}", 'controller');
                $r->addRoute('GET', "/{resource:$actionOnId}/{resourceId:\\w+}", 'controller');
                $r->addRoute('GET', "/{domainId:\\w+}/{resource:$singleAction}", 'controller');
                $r->addRoute('GET', "/{domainId:\\w+}/{resource:$actionOnId}/{resourceId:\\w+}", 'controller');
            });
            
            return new \Middlewares\FastRoute($dispatcher);
        };
        
        
        // execute the matching route
        $app['requestHandler'] = function ($app) {
            return new \Middlewares\RequestHandler($app);
        };
    }
}
