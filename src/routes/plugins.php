<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/api/random', function( Request $request, Response $response){
    $countsql = "SELECT '1' as COUNT";
    $datasql = "SELECT * 
                FROM ARTDATA 
                ORDER BY RAND()
                LIMIT :lim OFFSET :offset";

    $input=array();
    $data = getData ($countsql, $datasql, 1, 1, $input, $response);
    return $data;
});

$app->put('/api/logger', function( Request $request, Response $response){
    
    $data = json_decode($request->getBody(), true);

    $category = $data['category'] ?: $request->getParam('category');
    $value = $data['value'] ?: $request->getParam('value');

    $category = trim($category);
    $value = trim($value);
    
    if((isset($category) === true && $category === '')||(isset($value) === true && $value === ''))  {
    
        return $response
        ->withJson  (
                        array("msg" => "400 Bad Request"),
                        400
                    ); 
    
    }
    else {
    
        return logQuery($category, $value, $response);
    
    }

    
});

$app->get('/api/filter', function( Request $request, Response $response){

    $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;

    $author = (isset($_GET['au']) && $_GET['au'] > 0) ? $_GET['au'] : 0;
    $form = (isset($_GET['fo']) && $_GET['fo'] > 0) ? $_GET['fo'] : 0;
    $location = (isset($_GET['lo']) && $_GET['lo'] > 0) ? $_GET['lo'] : 0;
    $school = (isset($_GET['sc']) && $_GET['sc'] > 0) ? $_GET['sc'] : 0;
    $timeframe = (isset($_GET['ti']) && $_GET['ti'] > 0) ? $_GET['ti'] : 0;
    $type = (isset($_GET['ty']) && $_GET['ty'] > 0) ? $_GET['ty'] : 0;
    $input=array();

    if(!($author||$form||$location||$school||$timeframe||$type)){
        return $response
        ->withJson  (
                        array("msg" => "400 Bad Request"),
                        400
                    ); 
    }

    $datasql = "SELECT * 
                FROM ARTDATA 
                WHERE ";

    $countsql = "SELECT COUNT(*) AS COUNT 
                FROM ARTDATA 
                WHERE ";                

    if($author){
        $datasql.=" AUTHOR_ID = :au AND ";
        $countsql.=" AUTHOR_ID = :au AND ";
        array_push($input, array("key" => ":au","keyvalue" => $author));
    }
    if($form){
        $datasql.=" FORM_ID = :fo AND ";
        $countsql.=" FORM_ID = :fo AND ";
        array_push($input, array("key" => ":fo","keyvalue" => $form));
    }
    if($location){
        $datasql.=" LOCATION_ID = :lo AND ";
        $countsql.=" LOCATION_ID = :lo AND ";
        array_push($input, array("key" => ":lo","keyvalue" => $location));
    }
    if($school){
        $datasql.=" SCHOOL_ID = :sc AND ";
        $countsql.=" SCHOOL_ID = :sc AND ";
        array_push($input, array("key" => ":sc","keyvalue" => $school));
    }
    if($timeframe){
        $datasql.=" TIMEFRAME_ID = :ti AND ";
        $countsql.=" TIMEFRAME_ID = :ti AND ";
        array_push($input, array("key" => ":ti","keyvalue" => $timeframe));
    }
    if($type){
        $datasql.=" TYPE_ID = :ty AND ";
        $countsql.=" TYPE_ID = :ty AND ";
        array_push($input, array("key" => ":ty","keyvalue" => $type));
    }

    $datasql=substr($datasql, 0, -4)."ORDER BY ID ASC LIMIT :lim OFFSET :offset";
    $countsql=substr($countsql, 0, -4);
    $data = getData ($countsql, $datasql, $page, $limit, $input, $response);
    return $data;
});