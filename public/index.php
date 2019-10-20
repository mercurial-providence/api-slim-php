<?php
// # use Namespaces for HTTP request
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

// # include the Slim framework
require '../vendor/autoload.php';

// # include DB connection file
require '../src/config/db.php';

// # create new Slim instance
$app = new \Slim\App;

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
// # let Slim starts to run
// without run(), the api routes won't work
$app->run();