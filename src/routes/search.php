<?php
// use Namespaces for HTTP request
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/api/search', function( Request $request, Response $response){

    $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;

    //$keywords = $request->getAttribute('keywords');
    $keywords=isset($_GET["q"]) ? $_GET["q"] : "";
    $logged = logQuery('search', $keywords, $response);
    
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
                    AUTHOR_ID,
                    BORN_DIED,
                    FORM,
                    FORM_ID,
                    LOCATION,
                    LOCATION_ID,
                    SCHOOL,
                    SCHOOL_ID,
                    TIMEFRAME,
                    TIMEFRAME_ID,
                    TYPE,
                    TYPE_ID,
                    CONCAT_WS(',',
                    (CASE WHEN MATCH(TITLE) AGAINST (:keyword IN NATURAL LANGUAGE MODE) > 0 THEN 'TITLE' END),
                    (CASE WHEN MATCH(TECHNIQUE) AGAINST (:keyword IN NATURAL LANGUAGE MODE) > 0 THEN 'TECHNIQUE' END),
                    (CASE WHEN MATCH(AUTHOR) AGAINST (:keyword IN NATURAL LANGUAGE MODE) > 0 THEN 'AUTHOR' END),
                    (CASE WHEN MATCH(FORM) AGAINST (:keyword IN NATURAL LANGUAGE MODE) > 0 THEN 'FORM' END),
                    (CASE WHEN MATCH(LOCATION) AGAINST (:keyword IN NATURAL LANGUAGE MODE) > 0 THEN 'LOCATION' END),
                    (CASE WHEN MATCH(SCHOOL) AGAINST (:keyword IN NATURAL LANGUAGE MODE) > 0 THEN 'SCHOOL' END),
                    (CASE WHEN MATCH(TYPE) AGAINST (:keyword IN NATURAL LANGUAGE MODE) > 0 THEN 'TYPE' END)
                    ) as FOUND_IN
                    
                    FROM ARTDATA 
                    WHERE 
                    MATCH (TITLE) AGAINST (:keyword IN NATURAL LANGUAGE MODE) OR
                    MATCH (TECHNIQUE) AGAINST (:keyword IN NATURAL LANGUAGE MODE) OR
                    MATCH (AUTHOR) AGAINST (:keyword IN NATURAL LANGUAGE MODE) OR
                    MATCH (FORM) AGAINST (:keyword IN NATURAL LANGUAGE MODE) OR
                    MATCH (LOCATION) AGAINST (:keyword IN NATURAL LANGUAGE MODE) OR
                    MATCH (SCHOOL) AGAINST (:keyword IN NATURAL LANGUAGE MODE) OR
                    MATCH (TYPE) AGAINST (:keyword IN NATURAL LANGUAGE MODE)

                    LIMIT :lim OFFSET :offset
                ";
    

    $input=array();
    array_push($input, array("key" => ":keyword","keyvalue" => $keywords));
    $data = getData ($countsql, $datasql, $page, $limit, $input, $response);
    return $data;
});
