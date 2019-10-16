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

function getData ($countsql, $datasql, $page, $limit, $input, $response){
    try{
        $offset = ($page-1) * $limit; //calculate what data you want

        $db = new db();
        $db = $db->connect();
        $countQuery = $db->prepare( $countsql );
        $dataQuery = $db->prepare( $datasql );
        $dataQuery->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $dataQuery->bindParam(':offset', $offset, \PDO::PARAM_INT);

        while(sizeof($input)){
            $curr = array_pop($input);
            $dataQuery->bindParam($curr["key"], $curr["keyvalue"]);
            $countQuery->bindParam($curr["key"], $curr["keyvalue"]);
        }

        $dataQuery->execute();
        $countQuery->execute();
        $db = null; // clear db object
        $count = $countQuery->fetch(PDO::FETCH_ASSOC); 
        $data  = $dataQuery->fetchAll(PDO::FETCH_ASSOC);
        if($count['COUNT']>0&&count($data)){
            $data_arr=array();
            $data_arr["records"]=array();
            $data_arr["pagination"]=array();

            $data_arr["records"] = $data;
            $data_arr["pagination"] =   array(
                                                "count" => (int)$count['COUNT'],
                                                "page" => (int)$page,
                                                "limit" => (int)$limit,
                                                "totalpages" => (int)ceil($count['COUNT']/$limit)
                                            );
        return $response->withJson($data_arr,200); 
        }
        else{
            return $response->withJson  (
                                            array("msg" => "Nothing found."),
                                            204
                                        );
        }
    }catch( PDOException $e ) {
        //return '{"error": {"msg":' . $e->getMessage() . '}';
        return $response->withJson  (
                                        array("msg" => $e->getMessage()),
                                        500
                                    );
    } 
}

// # include Arts route
require '../src/routes/art.php';
require '../src/routes/search.php';
require '../src/routes/info.php';

// # capture all bad routes
$app->get('/[{path:.*}]', function  (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
return $response->withJson  (
                                array("msg" => "404"),
                                404
                            );
});
// # let Slim starts to run
// without run(), the api routes won't work
$app->run();