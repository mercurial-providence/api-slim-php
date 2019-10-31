<?php

//  Handling CORS with a simple lazy CORS
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

class CORSMiddleware{
    public function __invoke(Slim\Http\Request $request, Slim\Http\Response $response, callable $next){
        $response = $next($request, $response);
        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            //        ->withHeader("Access-Control-Allow-Headers", "Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers")
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization, application/json')
            //        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
            ->withHeader('Access-Control-Allow-Methods', 'GET, PUT')
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('X-Powered-By', 'Mercurial API');
    }
}

$app->add(new CORSMiddleware());
