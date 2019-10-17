<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


function getDataMod ($countsql, $datasql, $seek, $limit, $input, $response){
    try{
        $db = new db();
        $db = $db->connect();
        $countQuery = $db->prepare( $countsql );
        $dataQuery = $db->prepare( $datasql );
        $dataQuery->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $dataQuery->bindParam(':seek', $seek, \PDO::PARAM_INT);

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
            $lastEl = end($data);
            $data_arr["pagination"] =   array(
                                                "count" => (int)$count['COUNT'],
                                                "seek" => (int)$seek,
                                                "limit" => (int)$limit,
                                                "seekmarker" => (int)$lastEl['ID']
                                            );
                                            
        return $response->withJson($data_arr,200); 
        }
        else{
            return $response->withJson  (
                                            array("error" => true,
                                                  "message" => "Nothing found"),
                                            204
                                        );
        }
    }catch( PDOException $e ) {
        //return '{"error": {"msg":' . $e->getMessage() . '}';
        return $response->withJson  (
                                        array("error" => "System Error"),
                                        500
                                    );
    } 
}
$app->get('/test', function( Request $request, Response $response){
    $seek = (isset($_GET['seek']) && $_GET['seek'] > 0) ? $_GET['seek'] : 0;
    $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
    $dir = isset($_GET['dir']) ? $_GET['dir'] : 'next';

    $countsql = "SELECT COUNT(*) as COUNT FROM AUTHOR ";

    $datasql = "    SELECT
                        AU.ID, AU.AUTHOR, AU.BORN_DIED,
                        COALESCE(AR.CNT, 0) AS COUNT
                    FROM AUTHOR AU
                    LEFT JOIN
                    (
                        SELECT AUTHOR_ID, COUNT(*) AS CNT
                        FROM ART
                        GROUP BY AUTHOR_ID
                    ) AR
                        ON AU.ID = AR.AUTHOR_ID
                    WHERE AU.ID > :seek
                    ORDER BY AU.ID ASC
                    LIMIT :limit";

    $data = getDataMod ($countsql, $datasql, $seek, $limit, $input, $response);
    return $data;
});