<?php
// use Namespaces for HTTP request
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

// create new Slim instance
$app = new \Slim\App;



/**
 * Get All Art
 */
// create GET HTTP request
$app->get('/api/art', function( Request $request, Response $response){

    //Implementing Pagination
    //pls validate that are numbers
    $page = (isset($_GET['page']) && $_GET['page'] > 0) ? $_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
    $offset = ($page-1) * $limit; //calculate what data you want

    $countsql = "SELECT COUNT(*) as COUNT FROM ART";
    $datasql = "SELECT * FROM ART LIMIT :limit OFFSET :offset";
    
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
      

    // check if more than 0 record found
    if($num>0){
    
        // products array
        $data_arr=array();
        $data_arr["records"]=array();
    
        // retrieve our table contents
        // fetch() is faster than fetchAll()
        // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
        while ($row = $dataQuery->fetch(PDO::FETCH_ASSOC)){
            // extract row
            // this will make $row['name'] to
            // just $name only
            extract($row);
  
    
            array_push($data_arr["records"], $row);
        }
        $countData=array(
            "count" => $num,
            "page" => $page,
            "limit" => $limit
        );

        $data_arr["count"] = $countData;
        // set response code - 200 OK
        http_response_code(200);
    
        // show products data
        echo json_encode($data_arr);
    }
    
    else{
        // set response code - 404 Not found
        http_response_code(404);
    
        // tell the user no products found
        echo json_encode(
            array("message" => "No products found.")
        );
    }


    }catch( PDOException $e ) {

        // show error message as Json format
        echo '{"error": {"msg": ' . $e->getMessage() . '}';
    } 


/*     try {
        // Get DB Object
        $db = new db();

        // connect to DB
        $db = $db->connect();

        // query
        $stmt = $db->query( $datasql );
        $arts = $stmt->fetchAll( PDO::FETCH_OBJ );
        $db = null; // clear db object

        // print out the result as json format
        echo json_encode( $arts );    

        
    } catch( PDOException $e ) {

        // show error message as Json format
        echo '{"error": {"msg": ' . $e->getMessage() . '}';
    } */
});

/**
 * Get Single Art
 */
// create GET HTTP request
$app->get('/api/art/{id}', function( Request $request, Response $response){
    $id = $request->getAttribute('id');
  
    $sql = "SELECT * FROM ART WHERE id = $id";
  
    try {
      // Get DB Object
      $db = new db();
  
      // connect to DB
      $db = $db->connect();
  
      // query
      $stmt = $db->query( $sql );
      $art = $stmt->fetchAll( PDO::FETCH_OBJ );
      $db = null; // clear db object
  
      // print out the result as json format
      echo json_encode( $art );    
  
      
    } catch( PDOException $e ) {
  
      // show error message as Json format
      echo '{"error": {"msg": ' . $e->getMessage() . '}';
    }
});

/**
 * Add new art data
 */
// create POST HTTP request
$app->post('/api/art/add', function( Request $request, Response $response){

    // get the parameter from the form submit
    $title = $request->getParam('title');
    $date = $request->getParam('date');
    $technique = $request->getParam('technique');
    $url = $request->getParam('url');
    
    
  
    $sql = "INSERT INTO ART (TITLE, DATE, TECHNIQUE, URL) 
            VALUES(:title,:date,:technique,:url)";
            echo "hello";
  
    try {
      // Get DB Object
      $db = new db();
  
      // connect to DB
      $db = $db->connect();
  
      // https://www.php.net/manual/en/pdo.prepare.php
      $stmt = $db->prepare( $sql );
  
      // bind each paramenter
      // https://www.php.net/manual/en/pdostatement.bindparam.php
      $stmt->bindParam(':title', $title);
      $stmt->bindParam(':date', $date);
      $stmt->bindParam(':technique', $technique);
      $stmt->bindParam(':url', $url);
  
      // execute sql
      $stmt->execute();
      
      // return the message as json format
      echo '{"notice" : {"msg" : "New Art Added."}';
  
    } catch( PDOException $e ) {
  
      // return error message as Json format
      echo '{"error": {"msg": ' . $e->getMessage() . '}';
    }
  
  });


  /**
 * Update a Single Customer data
 */
// create PUT HTTP request
$app->put('/api/art/update/{id}', function( Request $request, Response $response){
    // get attribute from URL
    $id = $request->getAttribute('id');
    
    // get the parameter from the form submit
    $title = $request->getParam('title');
    $date = $request->getParam('date');
    $technique = $request->getParam('technique');
    $url = $request->getParam('url');
    
  
    $sql = "UPDATE ART SET 
            title = :title,
            date = :date,
            technique = :technique,
            url = :url
            WHERE id = $id";
  
    try {
      // Get DB Object
      $db = new db();
  
      // connect to DB
      $db = $db->connect();
  
      // https://www.php.net/manual/en/pdo.prepare.php
      $stmt = $db->prepare( $sql );
  
      // bind each paramenter
      // https://www.php.net/manual/en/pdostatement.bindparam.php
      $stmt->bindParam(':title', $title);
      $stmt->bindParam(':date', $date);
      $stmt->bindParam(':technique', $technique);
      $stmt->bindParam(':url', $url);
  
      // execute sql
      $stmt->execute();
      
      // return the message as json format
      echo '{"notice" : {"msg" : "New Art Updated."}';
  
    } catch( PDOException $e ) {
  
      // return error message as Json format
      echo '{"error": {"msg": ' . $e->getMessage() . '}';
    }
  
  });

  /**
 * Delete a Single Customer data
 */
// create DELETE HTTP request
$app->delete('/api/art/delete/{id}', function( Request $request, Response $response){
    // get attribute from URL
    $id = $request->getAttribute('id');   
  
    $sql = "DELETE FROM ART WHERE id = $id";
  
    try {
      // Get DB Object
      $db = new db();
  
      // connect to DB
      $db = $db->connect();
  
      $stmt = $db->prepare($sql);  
  
      // execute sql
      $stmt->execute();
      $db = null;
      
      // return the message as json format
      echo '{"notice" : {"msg" : "New Art Deleted.."}';
  
    } catch( PDOException $e ) {
  
      // return error message as Json format
      echo '{"error": {"msg": ' . $e->getMessage() . '}';
    }
  
  });