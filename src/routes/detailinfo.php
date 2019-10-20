<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

function getDetailsData ($countsql, $datasql, $page, $limit, $input, $response){
    try{
        $offset = ($page-1) * $limit; //calculate what data you want

        $db = new db();
        $db = $db->connect();
        $countQuery = $db->prepare( $countsql );
        $dataQuery = $db->prepare( $datasql );
        $dataQuery->bindParam(':lim', $limit, \PDO::PARAM_INT);
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
        if($count['COUNT']>0){
            $data_arr=array();
            $data_arr["records"]=array();
            $data_arr["pagination"]=array();

// APPROACH TWO
              while ($row = $dataQuery->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                //print_r($row);
                $row_array=array();
                foreach ($row as $key => $row_el){ 
                    $expand = explode('##', $row_el);
                    $expand_NEW = array();
                    foreach($expand as $deepkey){
                        $expand_t=explode('&#', $deepkey);
                        if(count($expand_t)>1){
                            $temp=array(
                                "ID" => (int)$expand_t[0],
                                "VALUE" => (string)$expand_t[1]
                            );
                            array_push($expand_NEW, $temp) ;
                        }else{
                            array_push($expand_NEW, $expand_t) ;
                        }
                    }
                    if(count($expand_NEW[0])==1) $expand_NEW=$row_el;
                    $row_array[$key] = $expand_NEW;
                }
                array_push($data_arr["records"], $row_array);
            }  

            $data_arr["pagination"] =   array(
                                                "count" => (int)$count['COUNT'],
                                                "page" => (int)$page,
                                                "limit" => (int)$limit,
                                                "totalpages" => (int)ceil($count['COUNT']/$limit)
                                            );
        if(!count($data_arr["records"])) goto nocontent;
        return $response
                    ->withHeader('Content-Type','application/json')
                    ->withHeader('X-Powered-By','Mercurial API')
                    ->withJson($data_arr, 200); 
        }
        else{
            nocontent:
            return $response
            ->withHeader('Content-Type','application/json')
            ->withHeader('X-Powered-By','Mercurial API')
            ->withJson  (
                            array("msg" => "204 No Content"),
                            204
                        );
        }
    }catch( PDOException $e ) {
        //return '{"error": {"msg":' . $e->getMessage() . '}';
        return $response
        ->withHeader('Content-Type','application/json')
        ->withHeader('X-Powered-By','Mercurial API')
        ->withJson  (
                        array("msg" => $e->getMessage()),
                        500
                    );
    } 
}



// API CALLS
$app->group('/api/detailinfo', function () use ($app) {

	//AUTHOR INFORMATION
	$app->group('/author', function () use ($app) {
		$app->get('', function( Request $request, Response $response){
			$page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
			$limit = isset($_GET['limit']) ? $_GET['limit'] : 10;

			$countsql = "SELECT COUNT(*) as COUNT 
				FROM AUTHOR AU
				LEFT JOIN
				(
					SELECT AUTHOR_ID, COUNT(*) AS CNT
					FROM ART
					GROUP BY AUTHOR_ID
				) AR
				ON AU.ID = AR.AUTHOR_ID
				WHERE AR.CNT > 0";

			$datasql = "    SELECT
				AU.ID, AU.AUTHOR, AU.BORN_DIED, 
				COALESCE(AR.CNT, 0) AS COUNT,

				AR.FORM,
				AR.SCHOOL,
				AR.LOCATION,
				AR.TIMEFRAME,
				AR.TYPE

				FROM AUTHOR AU
				LEFT JOIN
				(
					SELECT AUTHOR_ID, COUNT(ID) AS CNT, 

					GROUP_CONCAT(DISTINCT FORM_ID, '&#', FORM  SEPARATOR '##') as 'FORM',
					GROUP_CONCAT(DISTINCT SCHOOL_ID, '&#', SCHOOL SEPARATOR '##') as 'SCHOOL',
					GROUP_CONCAT(DISTINCT LOCATION_ID, '&#', LOCATION SEPARATOR '##') as 'LOCATION',
					GROUP_CONCAT(DISTINCT TIMEFRAME_ID, '&#', TIMEFRAME SEPARATOR '##') as 'TIMEFRAME',
					GROUP_CONCAT(DISTINCT TYPE_ID, '&#', TYPE SEPARATOR '##') as 'TYPE'

					FROM ARTDATA
					GROUP BY AUTHOR_ID
				) AR
				ON AU.ID = AR.AUTHOR_ID
				WHERE AR.CNT > 0
				ORDER BY AU.AUTHOR ASC
				LIMIT :lim OFFSET :offset";
	/*
	    $input=array();
	    array_push($input, array("key" => ":keyword","keyvalue" => "ALLERGY"));
	 */  

			$data = getDetailsData ($countsql, $datasql, $page, $limit, $input, $response);
			return $data;
		});

		$app->get('/{id:[0-9]+}', function( Request $request, Response $response){
			$id = $request->getAttribute('id');
			$page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
			$limit = isset($_GET['limit']) ? $_GET['limit'] : 10;

			$countsql = "SELECT COUNT(*) as COUNT FROM AUTHOR WHERE ID = :id";

			$datasql = "    SELECT
				AU.ID, AU.AUTHOR, AU.BORN_DIED, 
				COALESCE(AR.CNT, 0) AS COUNT,
				AR.FORM,
				AR.SCHOOL,
				AR.LOCATION,
				AR.TIMEFRAME,
				AR.TYPE
				FROM AUTHOR AU
				LEFT JOIN
				(
					SELECT AUTHOR_ID, COUNT(*) AS CNT,
					GROUP_CONCAT(DISTINCT FORM_ID, '&#', FORM  SEPARATOR '##') as 'FORM',
					GROUP_CONCAT(DISTINCT SCHOOL_ID, '&#', SCHOOL SEPARATOR '##') as 'SCHOOL',
					GROUP_CONCAT(DISTINCT LOCATION_ID, '&#', LOCATION SEPARATOR '##') as 'LOCATION',
					GROUP_CONCAT(DISTINCT TIMEFRAME_ID, '&#', TIMEFRAME SEPARATOR '##') as 'TIMEFRAME',
					GROUP_CONCAT(DISTINCT TYPE_ID, '&#', TYPE SEPARATOR '##') as 'TYPE'

					FROM ARTDATA
					GROUP BY AUTHOR_ID
				) AR
				ON AU.ID = AR.AUTHOR_ID
				WHERE AU.ID = :id 
				ORDER BY AU.AUTHOR ASC
				LIMIT :lim OFFSET :offset";                      

			$input=array();
			array_push($input, array("key" => ":id","keyvalue" => $id));


			$data = getDetailsData ($countsql, $datasql, $page, $limit, $input, $response);
			return $data;
		});

		$app->get('/{char:[a-z]}', function( Request $request, Response $response){
			$char = "{$request->getAttribute('char')}%";
			$page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
			$limit = isset($_GET['limit']) ? $_GET['limit'] : 10;

			$countsql = "SELECT COUNT(*) as COUNT 
				FROM AUTHOR AU
				LEFT JOIN
				(
					SELECT AUTHOR_ID, COUNT(*) AS CNT
					FROM ART
					GROUP BY AUTHOR_ID
				) AR
				ON AU.ID = AR.AUTHOR_ID
				WHERE AR.CNT > 0 AND AUTHOR LIKE :char";

			$datasql = "    SELECT
				AU.ID, AU.AUTHOR, AU.BORN_DIED, 
				COALESCE(AR.CNT, 0) AS COUNT,

				AR.FORM,
				AR.SCHOOL,
				AR.LOCATION,
				AR.TIMEFRAME,
				AR.TYPE
				FROM AUTHOR AU
				LEFT JOIN
				(
					SELECT AUTHOR_ID, COUNT(*) AS CNT,
					GROUP_CONCAT(DISTINCT FORM_ID, '&#', FORM  SEPARATOR '##') as 'FORM',
					GROUP_CONCAT(DISTINCT SCHOOL_ID, '&#', SCHOOL SEPARATOR '##') as 'SCHOOL',
					GROUP_CONCAT(DISTINCT LOCATION_ID, '&#', LOCATION SEPARATOR '##') as 'LOCATION',
					GROUP_CONCAT(DISTINCT TIMEFRAME_ID, '&#', TIMEFRAME SEPARATOR '##') as 'TIMEFRAME',
					GROUP_CONCAT(DISTINCT TYPE_ID, '&#', TYPE SEPARATOR '##') as 'TYPE'

					FROM ARTDATA
					GROUP BY AUTHOR_ID
				) AR
				ON AU.ID = AR.AUTHOR_ID
				WHERE AU.AUTHOR LIKE :char AND AR.CNT > 0
				ORDER BY AU.AUTHOR ASC
				LIMIT :lim OFFSET :offset";    
			$input=array();
			array_push($input, array("key" => ":char","keyvalue" => $char));


			$data = getDetailsData ($countsql, $datasql, $page, $limit, $input, $response);
			return $data;
		});

	});    

	//TYPE INFORMATION
	$app->group('/type', function () use ($app) {
		$app->get('', function( Request $request, Response $response){
			$page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
			$limit = isset($_GET['limit']) ? $_GET['limit'] : 10;

			$countsql = "SELECT COUNT(*) as COUNT 
				FROM TYPE TY
				LEFT JOIN
				(
					SELECT TYPE_ID, COUNT(*) AS CNT
					FROM ART
					GROUP BY TYPE_ID
				) AR
				ON TY.ID = AR.TYPE_ID
				WHERE AR.CNT > 0";
/*             $datasql = "SELECT * , (SELECT COUNT(*) FROM ART WHERE ART.TYPE_ID = TYPE.ID) as COUNT 
FROM TYPE LIMIT :lim OFFSET :offset"; */
			$datasql = "    SELECT
				TY.ID, TY.TYPE,
				(SELECT URL FROM ART WHERE ART.ID = TY.FIMAGE) as FIMAGE,
				COALESCE(AR.CNT, 0) AS COUNT,

				AR.AUTHOR,
				AR.FORM,
				AR.SCHOOL,
				AR.LOCATION,
				AR.TIMEFRAME
				FROM TYPE TY
				LEFT JOIN
				(
					SELECT TYPE_ID, COUNT(*) AS CNT,
					GROUP_CONCAT(DISTINCT AUTHOR_ID, '&#', AUTHOR  SEPARATOR '##') as 'AUTHOR',
					GROUP_CONCAT(DISTINCT FORM_ID, '&#', FORM  SEPARATOR '##') as 'FORM',
					GROUP_CONCAT(DISTINCT SCHOOL_ID, '&#', SCHOOL SEPARATOR '##') as 'SCHOOL',
					GROUP_CONCAT(DISTINCT LOCATION_ID, '&#', LOCATION SEPARATOR '##') as 'LOCATION',
					GROUP_CONCAT(DISTINCT TIMEFRAME_ID, '&#', TIMEFRAME SEPARATOR '##') as 'TIMEFRAME'

					FROM ARTDATA
					GROUP BY TYPE_ID
				) AR
				ON TY.ID = AR.TYPE_ID
				WHERE AR.CNT > 0
				ORDER BY TY.TYPE ASC
				LIMIT :lim OFFSET :offset";
	/*
	    $input=array();
	    array_push($input, array("key" => ":keyword","keyvalue" => "ALLERGY"));
	 */  

			$data = getDetailsData ($countsql, $datasql, $page, $limit, $input, $response);
			return $data;
		});

		$app->get('/{id:[0-9]+}', function( Request $request, Response $response){
			$id = $request->getAttribute('id');
			$page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
			$limit = isset($_GET['limit']) ? $_GET['limit'] : 10;

			$countsql = "SELECT COUNT(*) as COUNT FROM TYPE WHERE ID = :id";
/*             $datasql = "SELECT *, (SELECT COUNT(*) FROM ART WHERE ART.TYPE_ID = TYPE.ID) as COUNT 
FROM TYPE WHERE ID = :id LIMIT :lim OFFSET :offset"; */
			$datasql = "    SELECT
				TY.ID, TY.TYPE,
				(SELECT URL FROM ART WHERE ART.ID = TY.FIMAGE) as FIMAGE,
				COALESCE(AR.CNT, 0) AS COUNT,

				AR.AUTHOR,
				AR.FORM,
				AR.SCHOOL,
				AR.LOCATION,
				AR.TIMEFRAME
				FROM TYPE TY
				LEFT JOIN
				(
					SELECT TYPE_ID, COUNT(*) AS CNT,
					GROUP_CONCAT(DISTINCT AUTHOR_ID, '&#', AUTHOR  SEPARATOR '##') as 'AUTHOR',
					GROUP_CONCAT(DISTINCT FORM_ID, '&#', FORM  SEPARATOR '##') as 'FORM',
					GROUP_CONCAT(DISTINCT SCHOOL_ID, '&#', SCHOOL SEPARATOR '##') as 'SCHOOL',
					GROUP_CONCAT(DISTINCT LOCATION_ID, '&#', LOCATION SEPARATOR '##') as 'LOCATION',
					GROUP_CONCAT(DISTINCT TIMEFRAME_ID, '&#', TIMEFRAME SEPARATOR '##') as 'TIMEFRAME'

					FROM ARTDATA
					GROUP BY TYPE_ID
				) AR
				ON TY.ID = AR.TYPE_ID
				WHERE TY.ID = :id
				ORDER BY TY.TYPE ASC
				LIMIT :lim OFFSET :offset";        
			$input=array();
			array_push($input, array("key" => ":id","keyvalue" => $id));


			$data = getDetailsData ($countsql, $datasql, $page, $limit, $input, $response);
			return $data;
		});
	});    

	//SCHOOL INFORMATION
	$app->group('/school', function () use ($app) {
		$app->get('', function( Request $request, Response $response){
			$page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
			$limit = isset($_GET['limit']) ? $_GET['limit'] : 10;

			$countsql = "SELECT COUNT(*) as COUNT 
				FROM SCHOOL SC
				LEFT JOIN
				(
					SELECT SCHOOL_ID, COUNT(*) AS CNT
					FROM ART
					GROUP BY SCHOOL_ID
				) AR
				ON SC.ID = AR.SCHOOL_ID
				WHERE AR.CNT > 0";
/*             $datasql = "SELECT *, (SELECT COUNT(*) FROM ART WHERE ART.SCHOOL_ID = SCHOOL.ID) as COUNT 
FROM SCHOOL LIMIT :lim OFFSET :offset"; */
			$datasql = "    SELECT
				SC.ID, SC.SCHOOL,
				(SELECT URL FROM ART WHERE ART.ID = SC.FIMAGE) as FIMAGE,
				COALESCE(AR.CNT, 0) AS COUNT,

				AR.AUTHOR,
				AR.FORM,
				AR.LOCATION,
				AR.TIMEFRAME,
				AR.TYPE
				FROM SCHOOL SC
				LEFT JOIN
				(
					SELECT SCHOOL_ID, COUNT(*) AS CNT,
					GROUP_CONCAT(DISTINCT AUTHOR_ID, '&#', AUTHOR  SEPARATOR '##') as 'AUTHOR',
					GROUP_CONCAT(DISTINCT FORM_ID, '&#', FORM  SEPARATOR '##') as 'FORM',
					GROUP_CONCAT(DISTINCT LOCATION_ID, '&#', LOCATION SEPARATOR '##') as 'LOCATION',
					GROUP_CONCAT(DISTINCT TIMEFRAME_ID, '&#', TIMEFRAME SEPARATOR '##') as 'TIMEFRAME',
					GROUP_CONCAT(DISTINCT TYPE_ID, '&#', TYPE SEPARATOR '##') as 'TYPE'

					FROM ARTDATA                                GROUP BY SCHOOL_ID
				) AR
				ON SC.ID = AR.SCHOOL_ID
				WHERE AR.CNT > 0
				ORDER BY SC.SCHOOL ASC
				LIMIT :lim OFFSET :offset";                        
	/*
	    $input=array();
	    array_push($input, array("key" => ":keyword","keyvalue" => "ALLERGY"));
	 */  

			$data = getDetailsData ($countsql, $datasql, $page, $limit, $input, $response);
			return $data;
		});

		$app->get('/{id:[0-9]+}', function( Request $request, Response $response){
			$id = $request->getAttribute('id');
			$page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
			$limit = isset($_GET['limit']) ? $_GET['limit'] : 10;

			$countsql = "SELECT COUNT(*) as COUNT FROM SCHOOL WHERE ID = :id";
/*             $datasql = "SELECT *, (SELECT COUNT(*) FROM ART WHERE ART.SCHOOL_ID = SCHOOL.ID) as COUNT 
FROM SCHOOL WHERE ID = :id LIMIT :lim OFFSET :offset"; */
			$datasql = "    SELECT
				SC.ID, SC.SCHOOL,
				(SELECT URL FROM ART WHERE ART.ID = SC.FIMAGE) as FIMAGE,
				COALESCE(AR.CNT, 0) AS COUNT,

				AR.AUTHOR,
				AR.FORM,
				AR.LOCATION,
				AR.TIMEFRAME,
				AR.TYPE
				FROM SCHOOL SC
				LEFT JOIN
				(
					SELECT SCHOOL_ID, COUNT(*) AS CNT,
					GROUP_CONCAT(DISTINCT AUTHOR_ID, '&#', AUTHOR  SEPARATOR '##') as 'AUTHOR',
					GROUP_CONCAT(DISTINCT FORM_ID, '&#', FORM  SEPARATOR '##') as 'FORM',
					GROUP_CONCAT(DISTINCT LOCATION_ID, '&#', LOCATION SEPARATOR '##') as 'LOCATION',
					GROUP_CONCAT(DISTINCT TIMEFRAME_ID, '&#', TIMEFRAME SEPARATOR '##') as 'TIMEFRAME',
					GROUP_CONCAT(DISTINCT TYPE_ID, '&#', TYPE SEPARATOR '##') as 'TYPE'

					FROM ARTDATA                                GROUP BY SCHOOL_ID
				) AR
				ON SC.ID = AR.SCHOOL_ID
				WHERE SC.ID = :id
				ORDER BY SC.SCHOOL ASC
				LIMIT :lim OFFSET :offset";                           

			$input=array();
			array_push($input, array("key" => ":id","keyvalue" => $id));


			$data = getDetailsData ($countsql, $datasql, $page, $limit, $input, $response);
			return $data;
		});
	});

	//TIMEFRAME INFORMATION
	$app->group('/timeframe', function () use ($app) {
		$app->get('', function( Request $request, Response $response){
			$page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
			$limit = isset($_GET['limit']) ? $_GET['limit'] : 10;

			$countsql = "SELECT COUNT(*) as COUNT 
				FROM TIMEFRAME TI
				LEFT JOIN
				(
					SELECT TIMEFRAME_ID, COUNT(*) AS CNT
					FROM ART
					GROUP BY TIMEFRAME_ID
				) AR
				ON TI.ID = AR.TIMEFRAME_ID
				WHERE AR.CNT > 0";
/*             $datasql = "SELECT *, (SELECT COUNT(*) FROM ART WHERE ART.TIMEFRAME_ID = TIMEFRAME.ID) as COUNT 
FROM TIMEFRAME LIMIT :lim OFFSET :offset"; */
			$datasql = "    SELECT
				TI.ID, TI.TIMEFRAME,
				(SELECT URL FROM ART WHERE ART.ID = TI.FIMAGE) as FIMAGE,
				COALESCE(AR.CNT, 0) AS COUNT,

				AR.AUTHOR,
				AR.FORM,
				AR.SCHOOL,
				AR.LOCATION,
				AR.TYPE
				FROM TIMEFRAME TI
				LEFT JOIN
				(
					SELECT TIMEFRAME_ID, COUNT(*) AS CNT,
					GROUP_CONCAT(DISTINCT AUTHOR_ID, '&#', AUTHOR  SEPARATOR '##') as 'AUTHOR',
					GROUP_CONCAT(DISTINCT FORM_ID, '&#', FORM  SEPARATOR '##') as 'FORM',
					GROUP_CONCAT(DISTINCT SCHOOL_ID, '&#', SCHOOL SEPARATOR '##') as 'SCHOOL',
					GROUP_CONCAT(DISTINCT LOCATION_ID, '&#', LOCATION SEPARATOR '##') as 'LOCATION',
					GROUP_CONCAT(DISTINCT TYPE_ID, '&#', TYPE SEPARATOR '##') as 'TYPE'

					FROM ARTDATA                                GROUP BY TIMEFRAME_ID
				) AR
				ON TI.ID = AR.TIMEFRAME_ID
				WHERE AR.CNT > 0
				ORDER BY TI.TIMEFRAME ASC
				LIMIT :lim OFFSET :offset";               
	/*
	    $input=array();
	    array_push($input, array("key" => ":keyword","keyvalue" => "ALLERGY"));
	 */  

			$data = getDetailsData ($countsql, $datasql, $page, $limit, $input, $response);
			return $data;
		});

		$app->get('/{id:[0-9]+}', function( Request $request, Response $response){
			$id = $request->getAttribute('id');
			$page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
			$limit = isset($_GET['limit']) ? $_GET['limit'] : 10;

			$countsql = "SELECT COUNT(*) as COUNT FROM TIMEFRAME WHERE ID = :id";
/*             $datasql = "SELECT *, (SELECT COUNT(*) FROM ART WHERE ART.TIMEFRAME_ID = TIMEFRAME.ID) as COUNT  
FROM TIMEFRAME WHERE ID = :id LIMIT :lim OFFSET :offset"; */
			$datasql = "    SELECT
				TI.ID, TI.TIMEFRAME,
				(SELECT URL FROM ART WHERE ART.ID = TI.FIMAGE) as FIMAGE,
				COALESCE(AR.CNT, 0) AS COUNT,

				AR.AUTHOR,
				AR.FORM,
				AR.SCHOOL,
				AR.LOCATION,
				AR.TYPE
				FROM TIMEFRAME TI
				LEFT JOIN
				(
					SELECT TIMEFRAME_ID, COUNT(*) AS CNT,
					GROUP_CONCAT(DISTINCT AUTHOR_ID, '&#', AUTHOR  SEPARATOR '##') as 'AUTHOR',
					GROUP_CONCAT(DISTINCT FORM_ID, '&#', FORM  SEPARATOR '##') as 'FORM',
					GROUP_CONCAT(DISTINCT SCHOOL_ID, '&#', SCHOOL SEPARATOR '##') as 'SCHOOL',
					GROUP_CONCAT(DISTINCT LOCATION_ID, '&#', LOCATION SEPARATOR '##') as 'LOCATION',
					GROUP_CONCAT(DISTINCT TYPE_ID, '&#', TYPE SEPARATOR '##') as 'TYPE'

					FROM ARTDATA                                GROUP BY TIMEFRAME_ID
				) AR
				ON TI.ID = AR.TIMEFRAME_ID
				WHERE TI.ID = :id
				ORDER BY TI.TIMEFRAME ASC
				LIMIT :lim OFFSET :offset";                          

			$input=array();
			array_push($input, array("key" => ":id","keyvalue" => $id));


			$data = getDetailsData ($countsql, $datasql, $page, $limit, $input, $response);
			return $data;
		});
	});

	//LOCATION INFORMATION
	$app->group('/location', function () use ($app) {
		$app->get('', function( Request $request, Response $response){
			$page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
			$limit = isset($_GET['limit']) ? $_GET['limit'] : 10;

			$countsql = "SELECT COUNT(*) as COUNT 
				FROM LOCATION LO
				LEFT JOIN
				(
					SELECT LOCATION_ID, COUNT(*) AS CNT
					FROM ART
					GROUP BY LOCATION_ID
				) AR
				ON LO.ID = AR.LOCATION_ID
				WHERE AR.CNT > 0";
/*             $datasql = "SELECT *, (SELECT COUNT(*) FROM ART WHERE ART.LOCATION_ID = LOCATION.ID) as COUNT  
FROM LOCATION LIMIT :lim OFFSET :offset"; */
			$datasql = "    SELECT
				LO.ID, LO.LOCATION,
				(SELECT URL FROM ART WHERE ART.ID = LO.FIMAGE) as FIMAGE,
				COALESCE(AR.CNT, 0) AS COUNT,

				AR.AUTHOR,
				AR.FORM,
				AR.SCHOOL,
				AR.TIMEFRAME,
				AR.TYPE
				FROM LOCATION LO
				LEFT JOIN
				(
					SELECT LOCATION_ID, COUNT(*) AS CNT,
					GROUP_CONCAT(DISTINCT AUTHOR_ID, '&#', AUTHOR  SEPARATOR '##') as 'AUTHOR',
					GROUP_CONCAT(DISTINCT FORM_ID, '&#', FORM  SEPARATOR '##') as 'FORM',
					GROUP_CONCAT(DISTINCT SCHOOL_ID, '&#', SCHOOL SEPARATOR '##') as 'SCHOOL',
					GROUP_CONCAT(DISTINCT TIMEFRAME_ID, '&#', TIMEFRAME SEPARATOR '##') as 'TIMEFRAME',
					GROUP_CONCAT(DISTINCT TYPE_ID, '&#', TYPE SEPARATOR '##') as 'TYPE'

					FROM ARTDATA                                GROUP BY LOCATION_ID
				) AR
				ON LO.ID = AR.LOCATION_ID
				WHERE AR.CNT > 0
				ORDER BY LO.LOCATION ASC
				LIMIT :lim OFFSET :offset";                          
	/*
	    $input=array();
	    array_push($input, array("key" => ":keyword","keyvalue" => "ALLERGY"));
	 */  

			$data = getDetailsData ($countsql, $datasql, $page, $limit, $input, $response);
			return $data;
		});

		$app->get('/{id:[0-9]+}', function( Request $request, Response $response){
			$id = $request->getAttribute('id');
			$page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
			$limit = isset($_GET['limit']) ? $_GET['limit'] : 10;

			$countsql = "SELECT COUNT(*) as COUNT FROM LOCATION WHERE ID = :id";
/*             $datasql = "SELECT * , (SELECT COUNT(*) FROM ART WHERE ART.LOCATION_ID = LOCATION.ID) as COUNT 
FROM LOCATION WHERE ID = :id LIMIT :lim OFFSET :offset"; */
			$datasql = "    SELECT
				LO.ID, LO.LOCATION,
				(SELECT URL FROM ART WHERE ART.ID = LO.FIMAGE) as FIMAGE,
				COALESCE(AR.CNT, 0) AS COUNT,

				AR.AUTHOR,
				AR.FORM,
				AR.SCHOOL,
				AR.TIMEFRAME,
				AR.TYPE
				FROM LOCATION LO
				LEFT JOIN
				(
					SELECT LOCATION_ID, COUNT(*) AS CNT,
					GROUP_CONCAT(DISTINCT AUTHOR_ID, '&#', AUTHOR  SEPARATOR '##') as 'AUTHOR',
					GROUP_CONCAT(DISTINCT FORM_ID, '&#', FORM  SEPARATOR '##') as 'FORM',
					GROUP_CONCAT(DISTINCT SCHOOL_ID, '&#', SCHOOL SEPARATOR '##') as 'SCHOOL',
					GROUP_CONCAT(DISTINCT TIMEFRAME_ID, '&#', TIMEFRAME SEPARATOR '##') as 'TIMEFRAME',
					GROUP_CONCAT(DISTINCT TYPE_ID, '&#', TYPE SEPARATOR '##') as 'TYPE'

					FROM ARTDATA                                GROUP BY LOCATION_ID
				) AR
				ON LO.ID = AR.LOCATION_ID
				WHERE LO.ID = :id
				ORDER BY LO.LOCATION ASC
				LIMIT :lim OFFSET :offset";                          

			$input=array();
			array_push($input, array("key" => ":id","keyvalue" => $id));

			$data = getDetailsData ($countsql, $datasql, $page, $limit, $input, $response);
			return $data;
		});
	});


	//FORM INFORMATION
	$app->group('/form', function () use ($app) {
		$app->get('', function( Request $request, Response $response){
			$page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
			$limit = isset($_GET['limit']) ? $_GET['limit'] : 10;

			$countsql = "SELECT COUNT(*) as COUNT 
				FROM FORM FO
				LEFT JOIN
				(
					SELECT FORM_ID, COUNT(*) AS CNT
					FROM ART
					GROUP BY FORM_ID
				) AR
				ON FO.ID = AR.FORM_ID
				WHERE AR.CNT > 0";
/*             $datasql = "SELECT *, (SELECT COUNT(*) FROM ART WHERE ART.FORM_ID = FORM.ID) as COUNT 
FROM FORM LIMIT :lim OFFSET :offset"; */
			$datasql = "    SELECT
				FO.ID, FO.FORM,
				(SELECT URL FROM ART WHERE ART.ID = FO.FIMAGE) as FIMAGE,
				COALESCE(AR.CNT, 0) AS COUNT,

				AR.AUTHOR,
				AR.SCHOOL,
				AR.LOCATION,
				AR.TIMEFRAME,
				AR.TYPE
				FROM FORM FO
				LEFT JOIN
				(
					SELECT FORM_ID, COUNT(*) AS CNT,
					GROUP_CONCAT(DISTINCT AUTHOR_ID, '&#', AUTHOR  SEPARATOR '##') as 'AUTHOR',
					GROUP_CONCAT(DISTINCT SCHOOL_ID, '&#', SCHOOL SEPARATOR '##') as 'SCHOOL',
					GROUP_CONCAT(DISTINCT LOCATION_ID, '&#', LOCATION SEPARATOR '##') as 'LOCATION',
					GROUP_CONCAT(DISTINCT TIMEFRAME_ID, '&#', TIMEFRAME SEPARATOR '##') as 'TIMEFRAME',
					GROUP_CONCAT(DISTINCT TYPE_ID, '&#', TYPE SEPARATOR '##') as 'TYPE'

					FROM ARTDATA                                GROUP BY FORM_ID
				) AR
				ON FO.ID = AR.FORM_ID
				WHERE AR.CNT > 0
				ORDER BY FO.FORM ASC
				LIMIT :lim OFFSET :offset";                            
	/*
	    $input=array();
	    array_push($input, array("key" => ":keyword","keyvalue" => "ALLERGY"));
	 */  

			$data = getDetailsData ($countsql, $datasql, $page, $limit, $input, $response);
			return $data;
		});

		$app->get('/{id:[0-9]+}', function( Request $request, Response $response){
			$id = $request->getAttribute('id');
			$page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
			$limit = isset($_GET['limit']) ? $_GET['limit'] : 10;

			$countsql = "SELECT COUNT(*) as COUNT FROM FORM WHERE ID = :id";
/*             $datasql = "SELECT *, (SELECT COUNT(*) FROM ART WHERE ART.FORM_ID = FORM.ID) as COUNT 
FROM FORM WHERE ID = :id LIMIT :lim OFFSET :offset"; */
			$datasql = "    SELECT
				FO.ID, FO.FORM,
				(SELECT URL FROM ART WHERE ART.ID = FO.FIMAGE) as FIMAGE,
				COALESCE(AR.CNT, 0) AS COUNT,

				AR.AUTHOR,
				AR.SCHOOL,
				AR.LOCATION,
				AR.TIMEFRAME,
				AR.TYPE
				FROM FORM FO
				LEFT JOIN
				(
					SELECT FORM_ID, COUNT(*) AS CNT,
					GROUP_CONCAT(DISTINCT AUTHOR_ID, '&#', AUTHOR  SEPARATOR '##') as 'AUTHOR',
					GROUP_CONCAT(DISTINCT SCHOOL_ID, '&#', SCHOOL SEPARATOR '##') as 'SCHOOL',
					GROUP_CONCAT(DISTINCT LOCATION_ID, '&#', LOCATION SEPARATOR '##') as 'LOCATION',
					GROUP_CONCAT(DISTINCT TIMEFRAME_ID, '&#', TIMEFRAME SEPARATOR '##') as 'TIMEFRAME',
					GROUP_CONCAT(DISTINCT TYPE_ID, '&#', TYPE SEPARATOR '##') as 'TYPE'

					FROM ARTDATA                                GROUP BY FORM_ID
				) AR
				ON FO.ID = AR.FORM_ID
				WHERE FO.ID = :id
				ORDER BY FO.FORM ASC
				LIMIT :lim OFFSET :offset";                          

			$input=array();
			array_push($input, array("key" => ":id","keyvalue" => $id));


			$data = getDetailsData ($countsql, $datasql, $page, $limit, $input, $response);
			return $data;
		});
	});
});
