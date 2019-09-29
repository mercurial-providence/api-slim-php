<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->group('/api/info', function () use ($app) {

    //AUTHOR INFORMATION
    $app->group('/author', function () use ($app) {
        $app->get('', function( Request $request, Response $response){
            $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
        
            $countsql = "SELECT COUNT(*) as COUNT FROM AUTHOR";
            $datasql = "SELECT * FROM AUTHOR LIMIT :limit OFFSET :offset";
        /*
            $input=array();
            array_push($input, array("key" => ":keyword","keyvalue" => "ALLERGY"));
        */  
        
            $data = getData ($countsql, $datasql, $page, $limit, $input, $response);
            return $data;
        });

        $app->get('/{id:[0-9]+}', function( Request $request, Response $response){
            $id = $request->getAttribute('id');
            $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
        
            $countsql = "SELECT COUNT(*) as COUNT FROM AUTHOR WHERE ID = :id";
            $datasql = "SELECT * FROM AUTHOR WHERE ID = :id LIMIT :limit OFFSET :offset";
        
            $input=array();
            array_push($input, array("key" => ":id","keyvalue" => $id));
          
        
            $data = getData ($countsql, $datasql, $page, $limit, $input, $response);
            return $data;
        });

        $app->get('/{char:[a-z]}', function( Request $request, Response $response){
            $char = "{$request->getAttribute('char')}%";
            $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;

            $countsql = "SELECT COUNT(*) as COUNT FROM AUTHOR WHERE AUTHOR LIKE :char";
            $datasql = "SELECT * FROM AUTHOR WHERE AUTHOR LIKE :char LIMIT :limit OFFSET :offset";
        
            $input=array();
            array_push($input, array("key" => ":char","keyvalue" => $char));
          
        
            $data = getData ($countsql, $datasql, $page, $limit, $input, $response);
            return $data;
        });

    });    
    
    //TYPE INFORMATION
    $app->group('/type', function () use ($app) {
        $app->get('', function( Request $request, Response $response){
            $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
        
            $countsql = "SELECT COUNT(*) as COUNT FROM TYPE";
            $datasql = "SELECT * FROM TYPE LIMIT :limit OFFSET :offset";
        /*
            $input=array();
            array_push($input, array("key" => ":keyword","keyvalue" => "ALLERGY"));
        */  
        
            $data = getData ($countsql, $datasql, $page, $limit, $input, $response);
            return $data;
        });

        $app->get('/{id:[0-9]+}', function( Request $request, Response $response){
            $id = $request->getAttribute('id');
            $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
        
            $countsql = "SELECT COUNT(*) as COUNT FROM TYPE WHERE ID = :id";
            $datasql = "SELECT * FROM TYPE WHERE ID = :id LIMIT :limit OFFSET :offset";
        
            $input=array();
            array_push($input, array("key" => ":id","keyvalue" => $id));
          
        
            $data = getData ($countsql, $datasql, $page, $limit, $input, $response);
            return $data;
        });
    });    
    
    //SCHOOL INFORMATION
    $app->group('/school', function () use ($app) {
        $app->get('', function( Request $request, Response $response){
            $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
        
            $countsql = "SELECT COUNT(*) as COUNT FROM SCHOOL";
            $datasql = "SELECT * FROM SCHOOL LIMIT :limit OFFSET :offset";
        /*
            $input=array();
            array_push($input, array("key" => ":keyword","keyvalue" => "ALLERGY"));
        */  
        
            $data = getData ($countsql, $datasql, $page, $limit, $input, $response);
            return $data;
        });

        $app->get('/{id:[0-9]+}', function( Request $request, Response $response){
            $id = $request->getAttribute('id');
            $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
        
            $countsql = "SELECT COUNT(*) as COUNT FROM SCHOOL WHERE ID = :id";
            $datasql = "SELECT * FROM SCHOOL WHERE ID = :id LIMIT :limit OFFSET :offset";
        
            $input=array();
            array_push($input, array("key" => ":id","keyvalue" => $id));
          
        
            $data = getData ($countsql, $datasql, $page, $limit, $input, $response);
            return $data;
        });
    });

    //TIMEFRAME INFORMATION
    $app->group('/timeframe', function () use ($app) {
        $app->get('', function( Request $request, Response $response){
            $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
        
            $countsql = "SELECT COUNT(*) as COUNT FROM TIMEFRAME";
            $datasql = "SELECT * FROM TIMEFRAME LIMIT :limit OFFSET :offset";
        /*
            $input=array();
            array_push($input, array("key" => ":keyword","keyvalue" => "ALLERGY"));
        */  
        
            $data = getData ($countsql, $datasql, $page, $limit, $input, $response);
            return $data;
        });

        $app->get('/{id:[0-9]+}', function( Request $request, Response $response){
            $id = $request->getAttribute('id');
            $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
        
            $countsql = "SELECT COUNT(*) as COUNT FROM TIMEFRAME WHERE ID = :id";
            $datasql = "SELECT * FROM TIMEFRAME WHERE ID = :id LIMIT :limit OFFSET :offset";
        
            $input=array();
            array_push($input, array("key" => ":id","keyvalue" => $id));
          
        
            $data = getData ($countsql, $datasql, $page, $limit, $input, $response);
            return $data;
        });
    });

    //LOCATION INFORMATION
    $app->group('/location', function () use ($app) {
        $app->get('', function( Request $request, Response $response){
            $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
        
            $countsql = "SELECT COUNT(*) as COUNT FROM LOCATION";
            $datasql = "SELECT * FROM LOCATION LIMIT :limit OFFSET :offset";
        /*
            $input=array();
            array_push($input, array("key" => ":keyword","keyvalue" => "ALLERGY"));
        */  
        
            $data = getData ($countsql, $datasql, $page, $limit, $input, $response);
            return $data;
        });

        $app->get('/{id:[0-9]+}', function( Request $request, Response $response){
            $id = $request->getAttribute('id');
            $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
        
            $countsql = "SELECT COUNT(*) as COUNT FROM LOCATION WHERE ID = :id";
            $datasql = "SELECT * FROM LOCATION WHERE ID = :id LIMIT :limit OFFSET :offset";
        
            $input=array();
            array_push($input, array("key" => ":id","keyvalue" => $id));
          
        
            $data = getData ($countsql, $datasql, $page, $limit, $input, $response);
            return $data;
        });
    });


    //FORM INFORMATION
    $app->group('/form', function () use ($app) {
        $app->get('', function( Request $request, Response $response){
            $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
        
            $countsql = "SELECT COUNT(*) as COUNT FROM FORM";
            $datasql = "SELECT * FROM FORM LIMIT :limit OFFSET :offset";
        /*
            $input=array();
            array_push($input, array("key" => ":keyword","keyvalue" => "ALLERGY"));
        */  
        
            $data = getData ($countsql, $datasql, $page, $limit, $input, $response);
            return $data;
        });

        $app->get('/{id:[0-9]+}', function( Request $request, Response $response){
            $id = $request->getAttribute('id');
            $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
        
            $countsql = "SELECT COUNT(*) as COUNT FROM FORM WHERE ID = :id";
            $datasql = "SELECT * FROM FORM WHERE ID = :id LIMIT :limit OFFSET :offset";
        
            $input=array();
            array_push($input, array("key" => ":id","keyvalue" => $id));
          
        
            $data = getData ($countsql, $datasql, $page, $limit, $input, $response);
            return $data;
        });
    });

    //ART INFORMATION
    $app->group('/art', function () use ($app) {
        $app->get('', function( Request $request, Response $response){
            $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
        
            $countsql = "SELECT COUNT(*) as COUNT FROM ART";
            $datasql = "SELECT * FROM ART LIMIT :limit OFFSET :offset";
        /*
            $input=array();
            array_push($input, array("key" => ":keyword","keyvalue" => "ALLERGY"));
        */  
        
            $data = getData ($countsql, $datasql, $page, $limit, $input, $response);
            return $data;
        });

        $app->get('/{id:[0-9]+}', function( Request $request, Response $response){
            $id = $request->getAttribute('id');
            $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
        
            $countsql = "SELECT COUNT(*) as COUNT FROM ART WHERE ID = :id";
            $datasql = "SELECT * FROM ART WHERE ID = :id LIMIT :limit OFFSET :offset";
        
            $input=array();
            array_push($input, array("key" => ":id","keyvalue" => $id));
          
        
            $data = getData ($countsql, $datasql, $page, $limit, $input, $response);
            return $data;
        });
    });
});