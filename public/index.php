<?php

use Slim\Exception\HttpNotFoundException;

// # include the Slim framework
require __DIR__ . '/../vendor/autoload.php';

// # create new Slim instance
$app = new \Slim\App;

require __DIR__ . '/../src/autoload.php';

//  This should be the last fucntion, as per CORS policy.
//  http://www.slimframework.com/docs/v3/cookbook/enable-cors.html
$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
    throw new HttpNotFoundException($request);
});

// Let Slim starts to run. Without run(), the api routes won't work
$app->run();
