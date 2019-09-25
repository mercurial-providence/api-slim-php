<?php
// # use Namespaces for HTTP request
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

// # include the Slim framework
require '../vendor/autoload.php';

// # include DB connection file
require '../src/config/db.php';

// create new Slim instance
$app = new \Slim\App;

// # include Customers route
require '../src/routes/art.php';
require '../src/routes/search.php';
require '../src/routes/author.php';

// # let Slim starts to run
// without run(), the api routes won't work
$app->run();