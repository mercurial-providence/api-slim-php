<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->group('/api/info', function () use ($app) {

    //AUTHOR INFORMATION
    $app->group('/author', function () use ($app) {
        $app->get('', function (Request $request, Response $response) {
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
            /*             $datasql = "SELECT * , (SELECT COUNT(*) FROM ART WHERE ART.AUTHOR_ID = AUTHOR.ID) as COUNT 
                        FROM AUTHOR LIMIT :lim OFFSET :offset"; */
            $datasql = "    SELECT
                                AU.ID, AU.AUTHOR, AU.BORN_DIED,
                                AR.SCHOOL_ID, AR.SCHOOL,
                                COALESCE(AR.CNT, 0) AS COUNT
                            FROM AUTHOR AU
                            LEFT JOIN
                            (
                                SELECT AUTHOR_ID, SCHOOL_ID, SCHOOL,  COUNT(*) AS CNT
                                FROM ARTDATA
                                GROUP BY AUTHOR_ID
                            ) AR
                                ON AU.ID = AR.AUTHOR_ID
                            WHERE AR.CNT > 0
                            ORDER BY AU.ID ASC
                            LIMIT :lim OFFSET :offset";
            /*
            $input=array();
            array_push($input, array("key" => ":keyword","keyvalue" => "ALLERGY"));
        */

            $data = getData($countsql, $datasql, $page, $limit, $input, $response);
            return $data;
        });

        $app->get('/{id:[0-9]+}', function (Request $request, Response $response) {
            $id = $request->getAttribute('id');
            $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;

            $countsql = "SELECT COUNT(*) as COUNT FROM AUTHOR WHERE ID = :id";
            /*             $datasql = "SELECT *  , (SELECT COUNT(*) FROM ART WHERE ART.AUTHOR_ID = AUTHOR.ID) as COUNT 
                        FROM AUTHOR  WHERE ID = :id LIMIT :lim OFFSET :offset"; */
            $datasql = "    SELECT
                                AU.ID, AU.AUTHOR, AU.BORN_DIED, 
                                AR.SCHOOL_ID, AR.SCHOOL,
                                COALESCE(AR.CNT, 0) AS COUNT
                            FROM AUTHOR AU
                            LEFT JOIN
                            (
                                SELECT AUTHOR_ID, SCHOOL_ID, SCHOOL, COUNT(*) AS CNT
                                FROM ARTDATA
                                GROUP BY AUTHOR_ID
                            ) AR
                                ON AU.ID = AR.AUTHOR_ID
                            WHERE AU.ID = :id 
                            ORDER BY AU.ID ASC
                            LIMIT :lim OFFSET :offset";

            $input = array();
            array_push($input, array("key" => ":id", "keyvalue" => $id));


            $data = getData($countsql, $datasql, $page, $limit, $input, $response);
            return $data;
        });

        $app->get('/{char:[a-z]}', function (Request $request, Response $response) {
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
            /*            $datasql = "SELECT * , (SELECT COUNT(*) FROM ART WHERE ART.AUTHOR_ID = AUTHOR.ID) as COUNT 
                        FROM AUTHOR  WHERE AUTHOR LIKE :char LIMIT :lim OFFSET :offset"; */
            $datasql = "    SELECT
                                AU.ID, AU.AUTHOR, AU.BORN_DIED, 
                                AR.SCHOOL_ID, AR.SCHOOL,
                                COALESCE(AR.CNT, 0) AS COUNT
                            FROM AUTHOR AU
                            LEFT JOIN
                            (
                                SELECT AUTHOR_ID, SCHOOL_ID, SCHOOL, COUNT(*) AS CNT
                                FROM ARTDATA
                                GROUP BY AUTHOR_ID
                            ) AR
                                ON AU.ID = AR.AUTHOR_ID
                            WHERE AU.AUTHOR LIKE :char AND AR.CNT > 0
                            ORDER BY AU.AUTHOR ASC
                            LIMIT :lim OFFSET :offset";
            $input = array();
            array_push($input, array("key" => ":char", "keyvalue" => $char));


            $data = getData($countsql, $datasql, $page, $limit, $input, $response);
            return $data;
        });
    });

    //TYPE INFORMATION
    $app->group('/type', function () use ($app) {
        $app->get('', function (Request $request, Response $response) {
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
                                COALESCE(AR.CNT, 0) AS COUNT
                            FROM TYPE TY
                            LEFT JOIN
                            (
                                SELECT TYPE_ID, COUNT(*) AS CNT
                                FROM ART
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

            $data = getData($countsql, $datasql, $page, $limit, $input, $response);
            return $data;
        });

        $app->get('/{id:[0-9]+}', function (Request $request, Response $response) {
            $id = $request->getAttribute('id');
            $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;

            $countsql = "SELECT COUNT(*) as COUNT FROM TYPE WHERE ID = :id";
            /*             $datasql = "SELECT *, (SELECT COUNT(*) FROM ART WHERE ART.TYPE_ID = TYPE.ID) as COUNT 
                        FROM TYPE WHERE ID = :id LIMIT :lim OFFSET :offset"; */
            $datasql = "    SELECT
                                TY.ID, TY.TYPE,
                                (SELECT URL FROM ART WHERE ART.ID = TY.FIMAGE) as FIMAGE,
                                COALESCE(AR.CNT, 0) AS COUNT
                            FROM TYPE TY
                            LEFT JOIN
                            (
                                SELECT TYPE_ID, COUNT(*) AS CNT
                                FROM ART
                                GROUP BY TYPE_ID
                            ) AR
                                ON TY.ID = AR.TYPE_ID
                            WHERE TY.ID = :id
                            ORDER BY TY.TYPE ASC
                            LIMIT :lim OFFSET :offset";
            $input = array();
            array_push($input, array("key" => ":id", "keyvalue" => $id));


            $data = getData($countsql, $datasql, $page, $limit, $input, $response);
            return $data;
        });
    });

    //SCHOOL INFORMATION
    $app->group('/school', function () use ($app) {
        $app->get('', function (Request $request, Response $response) {
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
                                COALESCE(AR.CNT, 0) AS COUNT
                            FROM SCHOOL SC
                            LEFT JOIN
                            (
                                SELECT SCHOOL_ID, COUNT(*) AS CNT
                                FROM ART
                                GROUP BY SCHOOL_ID
                            ) AR
                                ON SC.ID = AR.SCHOOL_ID
                            WHERE AR.CNT > 0
                            ORDER BY SC.SCHOOL ASC
                            LIMIT :lim OFFSET :offset";
            /*
            $input=array();
            array_push($input, array("key" => ":keyword","keyvalue" => "ALLERGY"));
        */

            $data = getData($countsql, $datasql, $page, $limit, $input, $response);
            return $data;
        });

        $app->get('/{id:[0-9]+}', function (Request $request, Response $response) {
            $id = $request->getAttribute('id');
            $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;

            $countsql = "SELECT COUNT(*) as COUNT FROM SCHOOL WHERE ID = :id";
            /*             $datasql = "SELECT *, (SELECT COUNT(*) FROM ART WHERE ART.SCHOOL_ID = SCHOOL.ID) as COUNT 
                        FROM SCHOOL WHERE ID = :id LIMIT :lim OFFSET :offset"; */
            $datasql = "    SELECT
                                SC.ID, SC.SCHOOL,
                                (SELECT URL FROM ART WHERE ART.ID = SC.FIMAGE) as FIMAGE,
                                COALESCE(AR.CNT, 0) AS COUNT
                            FROM SCHOOL SC
                            LEFT JOIN
                            (
                                SELECT SCHOOL_ID, COUNT(*) AS CNT
                                FROM ART
                                GROUP BY SCHOOL_ID
                            ) AR
                                ON SC.ID = AR.SCHOOL_ID
                            WHERE SC.ID = :id
                            ORDER BY SC.SCHOOL ASC
                            LIMIT :lim OFFSET :offset";

            $input = array();
            array_push($input, array("key" => ":id", "keyvalue" => $id));


            $data = getData($countsql, $datasql, $page, $limit, $input, $response);
            return $data;
        });
    });

    //TIMEFRAME INFORMATION
    $app->group('/timeframe', function () use ($app) {
        $app->get('', function (Request $request, Response $response) {
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
                                COALESCE(AR.CNT, 0) AS COUNT
                            FROM TIMEFRAME TI
                            LEFT JOIN
                            (
                                SELECT TIMEFRAME_ID, COUNT(*) AS CNT
                                FROM ART
                                GROUP BY TIMEFRAME_ID
                            ) AR
                                ON TI.ID = AR.TIMEFRAME_ID
                            WHERE AR.CNT > 0
                            ORDER BY TI.TIMEFRAME ASC
                            LIMIT :lim OFFSET :offset";
            /*
            $input=array();
            array_push($input, array("key" => ":keyword","keyvalue" => "ALLERGY"));
        */

            $data = getData($countsql, $datasql, $page, $limit, $input, $response);
            return $data;
        });

        $app->get('/{id:[0-9]+}', function (Request $request, Response $response) {
            $id = $request->getAttribute('id');
            $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;

            $countsql = "SELECT COUNT(*) as COUNT FROM TIMEFRAME WHERE ID = :id";
            /*             $datasql = "SELECT *, (SELECT COUNT(*) FROM ART WHERE ART.TIMEFRAME_ID = TIMEFRAME.ID) as COUNT  
                        FROM TIMEFRAME WHERE ID = :id LIMIT :lim OFFSET :offset"; */
            $datasql = "    SELECT
                                TI.ID, TI.TIMEFRAME,
                                (SELECT URL FROM ART WHERE ART.ID = TI.FIMAGE) as FIMAGE,
                                COALESCE(AR.CNT, 0) AS COUNT
                            FROM TIMEFRAME TI
                            LEFT JOIN
                            (
                                SELECT TIMEFRAME_ID, COUNT(*) AS CNT
                                FROM ART
                                GROUP BY TIMEFRAME_ID
                            ) AR
                                ON TI.ID = AR.TIMEFRAME_ID
                            WHERE TI.ID = :id
                            ORDER BY TI.TIMEFRAME ASC
                            LIMIT :lim OFFSET :offset";

            $input = array();
            array_push($input, array("key" => ":id", "keyvalue" => $id));


            $data = getData($countsql, $datasql, $page, $limit, $input, $response);
            return $data;
        });
    });

    //LOCATION INFORMATION
    $app->group('/location', function () use ($app) {
        $app->get('', function (Request $request, Response $response) {
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
                                COALESCE(AR.CNT, 0) AS COUNT
                            FROM LOCATION LO
                            LEFT JOIN
                            (
                                SELECT LOCATION_ID, COUNT(*) AS CNT
                                FROM ART
                                GROUP BY LOCATION_ID
                            ) AR
                                ON LO.ID = AR.LOCATION_ID
                            WHERE AR.CNT > 0
                            ORDER BY LO.LOCATION ASC
                            LIMIT :lim OFFSET :offset";
            /*
            $input=array();
            array_push($input, array("key" => ":keyword","keyvalue" => "ALLERGY"));
        */

            $data = getData($countsql, $datasql, $page, $limit, $input, $response);
            return $data;
        });

        $app->get('/{id:[0-9]+}', function (Request $request, Response $response) {
            $id = $request->getAttribute('id');
            $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;

            $countsql = "SELECT COUNT(*) as COUNT FROM LOCATION WHERE ID = :id";
            /*             $datasql = "SELECT * , (SELECT COUNT(*) FROM ART WHERE ART.LOCATION_ID = LOCATION.ID) as COUNT 
                        FROM LOCATION WHERE ID = :id LIMIT :lim OFFSET :offset"; */
            $datasql = "    SELECT
                                LO.ID, LO.LOCATION,
                                (SELECT URL FROM ART WHERE ART.ID = LO.FIMAGE) as FIMAGE,
                                COALESCE(AR.CNT, 0) AS COUNT
                            FROM LOCATION LO
                            LEFT JOIN
                            (
                                SELECT LOCATION_ID, COUNT(*) AS CNT
                                FROM ART
                                GROUP BY LOCATION_ID
                            ) AR
                                ON LO.ID = AR.LOCATION_ID
                            WHERE LO.ID = :id
                            ORDER BY LO.LOCATION ASC
                            LIMIT :lim OFFSET :offset";

            $input = array();
            array_push($input, array("key" => ":id", "keyvalue" => $id));

            $data = getData($countsql, $datasql, $page, $limit, $input, $response);
            return $data;
        });
    });


    //FORM INFORMATION
    $app->group('/form', function () use ($app) {
        $app->get('', function (Request $request, Response $response) {
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
                                COALESCE(AR.CNT, 0) AS COUNT
                            FROM FORM FO
                            LEFT JOIN
                            (
                                SELECT FORM_ID, COUNT(*) AS CNT
                                FROM ART
                                GROUP BY FORM_ID
                            ) AR
                                ON FO.ID = AR.FORM_ID
                            WHERE AR.CNT > 0
                            ORDER BY FO.FORM ASC
                            LIMIT :lim OFFSET :offset";
            /*
            $input=array();
            array_push($input, array("key" => ":keyword","keyvalue" => "ALLERGY"));
        */

            $data = getData($countsql, $datasql, $page, $limit, $input, $response);
            return $data;
        });

        $app->get('/{id:[0-9]+}', function (Request $request, Response $response) {
            $id = $request->getAttribute('id');
            $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;

            $countsql = "SELECT COUNT(*) as COUNT FROM FORM WHERE ID = :id";
            /*             $datasql = "SELECT *, (SELECT COUNT(*) FROM ART WHERE ART.FORM_ID = FORM.ID) as COUNT 
                        FROM FORM WHERE ID = :id LIMIT :lim OFFSET :offset"; */
            $datasql = "    SELECT
                                FO.ID, FO.FORM,
                                (SELECT URL FROM ART WHERE ART.ID = FO.FIMAGE) as FIMAGE,
                                COALESCE(AR.CNT, 0) AS COUNT
                            FROM FORM FO
                            LEFT JOIN
                            (
                                SELECT FORM_ID, COUNT(*) AS CNT
                                FROM ART
                                GROUP BY FORM_ID
                            ) AR
                                ON FO.ID = AR.FORM_ID
                            WHERE FO.ID = :id
                            ORDER BY FO.FORM ASC
                            LIMIT :lim OFFSET :offset";

            $input = array();
            array_push($input, array("key" => ":id", "keyvalue" => $id));


            $data = getData($countsql, $datasql, $page, $limit, $input, $response);
            return $data;
        });
    });

    //ART INFORMATION
    $app->group('/art', function () use ($app) {
        $app->get('', function (Request $request, Response $response) {
            $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;



            $countsql = "SELECT COUNT(*) as COUNT FROM ART";
            $datasql = "    SELECT * 
                            FROM ART 
                            ORDER BY ART.ID ASC
                            LIMIT :lim OFFSET :offset";
            /*
            $input=array();
            array_push($input, array("key" => ":keyword","keyvalue" => "ALLERGY"));
        */

            $data = getData($countsql, $datasql, $page, $limit, $input, $response);
            return $data;
        });

        $app->get('/{id:[0-9]+}', function (Request $request, Response $response) {
            $id = $request->getAttribute('id');
            $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;

            $countsql = "SELECT COUNT(*) as COUNT FROM ART WHERE ID = :id";
            $datasql = "    SELECT * 
                            FROM ART 
                            WHERE ID = :id 
                            ORDER BY ART.ID ASC
                            LIMIT :lim OFFSET :offset";

            $input = array();
            array_push($input, array("key" => ":id", "keyvalue" => $id));


            $data = getData($countsql, $datasql, $page, $limit, $input, $response);
            return $data;
        });
    });
});
