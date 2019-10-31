<?php

function getData($countsql, $datasql, $page, $limit, $input, $response)
{
    try {
        $page = (int) $page;
        $limit = (int) $limit;
        $offset = ($page - 1) * $limit; //calculate what data you want

        $db = new db();
        $db = $db->connect();
        $countQuery = $db->prepare($countsql);
        $dataQuery = $db->prepare($datasql);
        $dataQuery->bindParam(':lim', $limit, PDO::PARAM_INT);
        $dataQuery->bindParam(':offset', $offset, PDO::PARAM_INT);

        while (sizeof($input)) {
            $curr = array_pop($input);
            $dataQuery->bindParam($curr["key"], $curr["keyvalue"]);
            $countQuery->bindParam($curr["key"], $curr["keyvalue"]);
        }

        $dataQuery->execute();
        $countQuery->execute();
        $db = null; // clear db object
        $count = $countQuery->fetch(PDO::FETCH_ASSOC);
        $data  = $dataQuery->fetchAll(PDO::FETCH_ASSOC);
        if ($count['COUNT'] > 0 && count($data)) {
            $data_arr = array();
            $data_arr["records"] = array();
            $data_arr["records"] = $data;
            $data_arr["pagination"] =   array(
                "count" => (int) $count['COUNT'],
                "page" => (int) $page,
                "limit" => (int) $limit,
                "totalpages" => (int) ceil($count['COUNT'] / $limit)
            );
            if (!count($data_arr["records"])) goto nocontent;
            return $response
                ->withJson($data_arr, 200);
        } else {
            nocontent: return $response
                ->withJson(
                    array("msg" => "204 No Content"),
                    204
                );
        }
    } catch (PDOException $e) {
        //return '{"error": {"msg":' . $e->getMessage() . '}';
        return $response
            ->withJson(
                array("msg" => $e->getMessage()),
                500
            );
    }
}

function seo_friendly_url($string, $length)
{
    $string = str_replace(array('[\', \']'), '', $string);
    $string = preg_replace('/\[.*\]/U', '', $string);
    $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '-', $string);
    $string = htmlentities($string, ENT_COMPAT, 'utf-8');
    $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string);
    $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/'), ' ', $string);
    $string = substr($string, 0, $length);
    return strtolower(trim($string, '-'));
}

function get_IP()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip_address = $_SERVER['REMOTE_ADDR'];
    }
    return $ip_address;
}

function logQuery($operation, $queryParam, $response)
{
    //$queryParam = htmlentities(urldecode($queryParam), ENT_COMPAT, 'utf-8');
    $queryParam = urldecode($queryParam);
    $ip = get_IP();
    $datasql = "INSERT INTO LOG_TABLE (CATEGORY, VALUE, IP) VALUES (:op, :param, :ip)";
    try {
        $db = new db();
        $db = $db->connect();
        $dataQuery = $db->prepare($datasql);
        $dataQuery->bindParam(':op', $operation);
        $dataQuery->bindParam(':param', $queryParam);
        $dataQuery->bindParam(':ip', $ip);
        $dataQuery->execute();
        $db = null; // clear db object
        return $response->withJson(
            array("msg" => "200 OK"),
            200
        );
    } catch (PDOException $e) {
        return $response->withJson(
            array("msg" => $e->getMessage()),
            500
        );
    }
}
