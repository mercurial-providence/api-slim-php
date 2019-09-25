<?php
// use Namespaces for HTTP request
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

// create new Slim instance

$app->get('/api/author', function( Request $request, Response $response){

    $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
    $offset = ($page-1) * $limit; //calculate what data you want

    $countsql = "SELECT COUNT(*) as COUNT FROM AUTHOR";
    $datasql = "SELECT * FROM AUTHOR LIMIT :limit OFFSET :offset";
    
    try{
      $db = new db();
      $db = $db->connect();
      $countQuery = $db->prepare( $countsql );
      $dataQuery = $db->prepare( $datasql );
      $dataQuery->bindParam(':limit', $limit, \PDO::PARAM_INT);
      $dataQuery->bindParam(':offset', $offset, \PDO::PARAM_INT);
      $dataQuery->execute();
      $countQuery->execute();
      $db = null; // clear db object
      $count = $countQuery->fetch(PDO::FETCH_ASSOC); 
      $num = $count['COUNT'];
    if($num>0){
        $data_arr=array();
        $data_arr["records"]=array();
        while ($row = $dataQuery->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            array_push($data_arr["records"], $row);
        }
        $countData=array(
            "count" => $num,
            "page" => $page,
            "limit" => $limit
        );

        $data_arr["count"] = $countData;
        http_response_code(200);
        echo json_encode($data_arr);
    }
    
    else{
        http_response_code(404);
        echo json_encode(
            array("message" => "No products found.")
        );
    }
    }catch( PDOException $e ) {
        echo '{"error": {"msg": ' . $e->getMessage() . '}';
    } 
});
