<?php
// use Namespaces for HTTP request
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/api/search', function( Request $request, Response $response){

    $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;

    //$keywords = $request->getAttribute('keywords');
    $keywords=isset($_GET["q"]) ? $_GET["q"] : "";
    logQuery('search', $keywords);
    $keywords = seo_friendly_url($keywords, 50);
    $countsql = "
                select 
                    COUNT(*) as COUNT

                    FROM ARTDATA 
                    WHERE 
                    MATCH (TITLE) AGAINST (:keyword IN NATURAL LANGUAGE MODE) OR
                    MATCH (TECHNIQUE) AGAINST (:keyword IN NATURAL LANGUAGE MODE) OR
                    MATCH (AUTHOR) AGAINST (:keyword IN NATURAL LANGUAGE MODE) OR
                    MATCH (FORM) AGAINST (:keyword IN NATURAL LANGUAGE MODE) OR
                    MATCH (LOCATION) AGAINST (:keyword IN NATURAL LANGUAGE MODE) OR
                    MATCH (SCHOOL) AGAINST (:keyword IN NATURAL LANGUAGE MODE) OR
                    MATCH (TYPE) AGAINST (:keyword IN NATURAL LANGUAGE MODE)
    
                ";
    $datasql = "
                    SELECT 

                    ID,
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
                    TYPE
                    
                    FROM ARTDATA 
                    WHERE 
                    MATCH (TITLE) AGAINST (:keyword IN NATURAL LANGUAGE MODE) OR
                    MATCH (TECHNIQUE) AGAINST (:keyword IN NATURAL LANGUAGE MODE) OR
                    MATCH (AUTHOR) AGAINST (:keyword IN NATURAL LANGUAGE MODE) OR
                    MATCH (FORM) AGAINST (:keyword IN NATURAL LANGUAGE MODE) OR
                    MATCH (LOCATION) AGAINST (:keyword IN NATURAL LANGUAGE MODE) OR
                    MATCH (SCHOOL) AGAINST (:keyword IN NATURAL LANGUAGE MODE) OR
                    MATCH (TYPE) AGAINST (:keyword IN NATURAL LANGUAGE MODE)

                    LIMIT :limit OFFSET :offset
                ";
    

    $input=array();
    array_push($input, array("key" => ":keyword","keyvalue" => $keywords));
    $data = getData ($countsql, $datasql, $page, $limit, $input, $response);
    return $data;
});
