<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';

// instantiate product object
include_once '../objects/order.php';

$database = new Database();
$db = $database->getConnection();

$order = new Order($db);

// get data
$data = json_decode(file_get_contents("https://1417d4f89a850524b3f9b21a009bcca7:9f5cfa6cf57a541754307fc85c540487@technical-be-8170798.myshopify.com/admin/orders.json?status=any&limit=250"));


// make sure data is not empty
if(!empty($data)){

    if($stmt = $order->dump($data)){

        http_response_code(201);
        echo json_encode(array("message" => "Complete"));
    }
    else{
        http_response_code(503);
        echo json_encode(array("message" => "Failure"));
    }
}

// tell the user data is incomplete
else{

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("message" => "No DATA"));
}

?>
