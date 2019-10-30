<?php
// # use Namespaces for HTTP request
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpNotFoundException;

// # include the Slim framework
require '../vendor/autoload.php';

// # include DB connection file
require '../src/config/db.php';

// # create new Slim instance
$app = new \Slim\App;


//  Handling CORS with a simple lazy CORS
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
//        ->withHeader("Access-Control-Allow-Headers", "Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers")
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization, application/json')
//        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
        ->withHeader('Access-Control-Allow-Methods', 'GET, PUT')
        ->withHeader('Content-Type','application/json')
        ->withHeader('X-Powered-By','Mercurial API');

});



// # importing functions
require '../src/functions/function.php';

// # include Arts route
require '../src/routes/art.php';
require '../src/routes/search.php';
require '../src/routes/info.php';
require '../src/routes/plugins.php';
require '../src/routes/detailinfo.php';
//require '../src/routes/test.php';

// # capture all bad routes
$app->get('/[{path:.*}]', function  (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
return $response->withJson  (
                                array("msg" => "404 Not Found"),
                                404
                            );
});


$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
    throw new HttpNotFoundException($request);
});

// # let Slim starts to run
// without run(), the api routes won't work
$app->run();