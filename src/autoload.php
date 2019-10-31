<?php

// Including DB connection
require __DIR__ . '/config/db.php';

// Enabling CORS
require __DIR__ . '/config/cors.php';

// Enabling Authentication
//require __DIR__ . '/config/auth.php';

// Importing functions
require __DIR__ . '/functions/function.php';

// Including Routes
require __DIR__ . '/routes/art.php';
require __DIR__ . '/routes/search.php';
require __DIR__ . '/routes/info.php';
require __DIR__ . '/routes/plugins.php';
require __DIR__ . '/routes/detailinfo.php';
//require '/routes/test.php';

// Capture all bad routes
$app->get('/[{path:.*}]', function (\Slim\Http\Request $request, \Slim\Http\Response $response, $args){
    return $response->withJson(
        array("msg" => "404 Not Found"),
        404
    );
});
