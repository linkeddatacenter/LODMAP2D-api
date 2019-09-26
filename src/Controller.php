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

use uSilex\Psr11Trait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

class Controller implements MiddlewareInterface
{
    use Psr11Trait;
    
    
    /**
     *  This function loads in a string an expanded php template.
     *  N.B. inside the required query, you can use $resource and $resourceId
     *  If template does not exists it raises an error
     */
    private function loadQuery($resource, $resourceId): String
    {
        ob_start();
        require(__DIR__ . "/Queries/{$resource}.php");
        return ob_get_clean();
    }
    
       
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $this->get('store')->request('POST', null, [
            'body' =>  $this->loadQuery(
                $request->getAttribute('resource'),
                $request->getAttribute('id')
            ),
            'headers' => [
                'Content-Type'  => 'application/sparql-query',
                'Accept'        => 'text/turtle'
            ]
        ]);
              
        return $response
            ->withoutHeader('Content-disposition')
            ->withoutHeader('Transfer-Encoding')
        ;
    }
}
