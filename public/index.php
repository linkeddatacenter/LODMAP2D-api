<?php
require_once __DIR__.'/../vendor/autoload.php';

use uSilex\Application;
use LODMAP2D\ApplicationServiceProvider;
use EnvProvider\EnvProvider;

$app = new Application();

// Register all services dependences
$app->register(new ApplicationServiceProvider());

// Optional overload of the container attributes from environment variables
$app->register(new EnvProvider(), [
    'env.prefix' => 'LODMAP2D_',
    'env.vars' => [
        'backend'               => 'env.cast.strval',	    // <= (string) LODMAP2D_BACKEND
        'CORS.AllowedOrigins'   => 'env.cast.strval',	    // <= (string) LODMAP2D_CORS_ALLOWEDORIGINS
        'cache.expire'          => 'env.cast.strval',	    // <= (string) LODMAP2D_CACHE_EXPIRE
    ]
 ]);

$app['env.overload']->run();
