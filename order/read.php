<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../objects/order.php';

// instantiate database and order object
$database = new Database();
$db = $database->getConnection();

// initialize object
$order = new Order($db);

// get posted data

$customerInput = null;
$variantInput = null;
$message = "Mean Value for all customers";

if(!empty($_GET["email"])) {

  $customerInput = htmlspecialchars($_GET["email"]);
  $message = "Mean order value for customer: ".$customerInput;

}
elseif (!empty($_GET["variant_id"])) {

  $variantInput = htmlspecialchars($_GET["variant_id"]);
  $message = "Mean order value for variant: ".$variantInput;

}

 $data = array('customer' => $customerInput, 'variant' => $variantInput);
// query orders
$stmt = $order->read($data);

$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

    // products array
    $orders_arr=array();
    $orders_arr["records"]=array();
    $values_arr=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

        extract($row);

        $order_item=array(
            "id" => $id,
            "customer" => $email,
            "value" => $total_price,
            "items" => $line_items

        );

        array_push(  $values_arr, $total_price);
        array_push($orders_arr["records"], $order_item);

    }
    $orders_mean = array(
        $message => getMeanAverage($values_arr)
    );
    array_push($orders_arr, $orders_mean);
    // set response code - 200 OK
    http_response_code(200);

    // show orders data in json format
    echo json_encode($orders_arr);
}
else{

    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no orders found
    echo json_encode(
        array("message" => "No orders found.")
    );
}
function getMeanAverage($array) {
      $average = array_sum($array) / count($array);
      return round($average,4);
}
