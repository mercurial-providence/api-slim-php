<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/api/random', function( Request $request, Response $response){
    $countsql = "SELECT '1' as COUNT";
    $datasql = "SELECT * 
                FROM ARTDATA 
                ORDER BY RAND()
                LIMIT :limit OFFSET :offset";

    $input=array();
    $data = getData ($countsql, $datasql, 1, 1, $input, $response);
    return $data;
});

