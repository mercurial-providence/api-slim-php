<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$paramsForQuery = " ART_ID,
                    TITLE,
                    DATE,
                    TECHNIQUE,
                    URL,
                    AUTHOR,
                    BORN_DIED,
                    FORM,
                    LOCATION,
                    SCHOOL,
                    TIMEFRAME,
                    TYPE ";
                    
$app->group('/api/art', function () use ($app) {

    $app->get('', function( Request $request, Response $response){
        $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
    
        $countsql = "SELECT COUNT(*) as COUNT FROM ART";
        $datasql = "SELECT * FROM ARTDATA LIMIT :limit OFFSET :offset";

        $data = getData ($countsql, $datasql, $page, $limit, $input);
        echo $data;
    });

    $app->get('/{id:[0-9]+}', function( Request $request, Response $response){
        $id = $request->getAttribute('id');
        $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
    
        $countsql = "SELECT COUNT(*) as COUNT FROM ART WHERE ID = :id";
        $datasql = "SELECT * FROM ARTDATA WHERE ART_ID = :id LIMIT :limit OFFSET :offset";
    
        $input=array();
        array_push($input, array("key" => ":id","keyvalue" => $id));
    
        $data = getData ($countsql, $datasql, $page, $limit, $input);
        echo $data;
    });

    //AUTHOR INFORMATION
    $app->group('/author', function () use ($app) {
        $app->get('/{id:[0-9]+}', function( Request $request, Response $response){
            $id = $request->getAttribute('id');
            $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
        
            $countsql = "SELECT COUNT(*) as COUNT FROM ARTDATA WHERE AUTHOR_ID = :id";
            $datasql = "SELECT * FROM ARTDATA WHERE AUTHOR_ID = :id LIMIT :limit OFFSET :offset";

            $input=array();
            array_push($input, array("key" => ":id","keyvalue" => $id));
          
        
            $data = getData ($countsql, $datasql, $page, $limit, $input);
            echo $data;
        });
    });    
    
    //TYPE INFORMATION
    $app->group('/type', function () use ($app) {
        $app->get('/{id:[0-9]+}', function( Request $request, Response $response){
            $id = $request->getAttribute('id');
            $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
        
            $countsql = "SELECT COUNT(*) as COUNT FROM ARTDATA WHERE TYPE_ID = :id";
            $datasql = "SELECT * FROM ARTDATA WHERE TYPE_ID = :id LIMIT :limit OFFSET :offset";
        
            $input=array();
            array_push($input, array("key" => ":id","keyvalue" => $id));
          
        
            $data = getData ($countsql, $datasql, $page, $limit, $input);
            echo $data;
        });
    });    
    
    //SCHOOL INFORMATION
    $app->group('/school', function () use ($app) {

        $app->get('/{id:[0-9]+}', function( Request $request, Response $response){
            $id = $request->getAttribute('id');
            $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
        
            $countsql = "SELECT COUNT(*) as COUNT FROM ARTDATA WHERE SCHOOL_ID = :id";
            $datasql = "SELECT * FROM ARTDATA WHERE SCHOOL_ID = :id LIMIT :limit OFFSET :offset";
        
            $input=array();
            array_push($input, array("key" => ":id","keyvalue" => $id));
          
        
            $data = getData ($countsql, $datasql, $page, $limit, $input);
            echo $data;
        });
    });

    //TIMEFRAME INFORMATION
    $app->group('/timeframe', function () use ($app) {

        $app->get('/{id:[0-9]+}', function( Request $request, Response $response){
            $id = $request->getAttribute('id');
            $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
        
            $countsql = "SELECT COUNT(*) as COUNT FROM ARTDATA WHERE TIMEFRAME_ID = :id";
            $datasql = "SELECT * FROM ARTDATA WHERE TIMEFRAME_ID = :id LIMIT :limit OFFSET :offset";
        
            $input=array();
            array_push($input, array("key" => ":id","keyvalue" => $id));
          
        
            $data = getData ($countsql, $datasql, $page, $limit, $input);
            echo $data;
        });
    });

    //LOCATION INFORMATION
    $app->group('/location', function () use ($app) {
        
        $app->get('/{id:[0-9]+}', function( Request $request, Response $response){
            $id = $request->getAttribute('id');
            $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
        
            $countsql = "SELECT COUNT(*) as COUNT FROM ARTDATA WHERE LOCATION_ID = :id";
            $datasql = "SELECT * FROM ARTDATA WHERE LOCATION_ID = :id LIMIT :limit OFFSET :offset";
        
            $input=array();
            array_push($input, array("key" => ":id","keyvalue" => $id));
          
        
            $data = getData ($countsql, $datasql, $page, $limit, $input);
            echo $data;
        });
    });


    //FORM INFORMATION
    $app->group('/form', function () use ($app) {
        
        $app->get('/{id:[0-9]+}', function( Request $request, Response $response){
            $id = $request->getAttribute('id');
            $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
        
            $countsql = "SELECT COUNT(*) as COUNT FROM ARTDATA WHERE FORM_ID = :id";
            $datasql = "SELECT * FROM ARTDATA WHERE FORM_ID = :id LIMIT :limit OFFSET :offset";
        
            $input=array();
            array_push($input, array("key" => ":id","keyvalue" => $id));
          
        
            $data = getData ($countsql, $datasql, $page, $limit, $input);
            echo $data;
        });
    });







































});