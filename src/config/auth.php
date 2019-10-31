<?php

class AuthenticationMiddleware{

    public function __invoke(Slim\Http\Request $request, Slim\Http\Response $response, callable $next){
        $key = $request->getHeader("redcat");
        print_r( $key);
        if ($key[0] === 'BLACK_LAB') {
            return $next($request, $response);
        } else {
            return $response
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('X-Powered-By', 'Mercurial API')
            ->withJson(
                    array("msg" => "401 Not Authorized"),
                    401
                );
        }
    }
}

// Apply the middleware to every request.
$app->add(new AuthenticationMiddleware());
