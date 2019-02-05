<?php
class Order{

    private $conn;
    private $table_name = "orders";



    public function __construct($db){
        $this->conn = $db;
    }

    function read($data = null){

      if(isset($data['customer'])) {

      $query = "SELECT
                   o.id, o.email, o.total_price, o.line_items
              FROM
                  " . $this->table_name . " o
              WHERE o.email=" .$data['customer'];

      } elseif(isset($data['variant'])) {

        $query = "SELECT o.id, o.email, o.total_price, o.line_items
                  FROM " . $this->table_name . " o
                  WHERE INSTR(`line_items`,".$data['variant']. ") > 0";
                  
      } else {
        $query = "SELECT
                     o.id, o.email, o.total_price, o.line_items
                FROM
                    " . $this->table_name . " o ";
      }

      $stmt = $this->conn->prepare($query);


      $stmt->execute();

      return $stmt;
  }
  function insert($json){


      // query to insert record
      $query = "INSERT INTO " . $this->table_name ." (";

                foreach($json as $key => $value){
                    $query .= $key . ",";
                }
                $query = substr_replace($query,"",-1);
            $query .= ") VALUES (";


                foreach($json as $key => $value){
                      if($key == "id") {
                        $query .= $value. ",";

                      }
                      elseif ($key == "line_items") {
                        $lineitems =  array();
                        foreach ($value as $likey => $livalue) {
                          foreach ($livalue as $fkey => $fvalue) {
                              if($fkey == "variant_id"){
                                array_push($lineitems, strval($fvalue));
                              }
                          }
                        }
                        $query .= "'" . implode( ',',$lineitems) . "',";
                      }
                      elseif(is_object($value) || is_array($value))

                        $query .= "'" . serialize($value) . "',";


                      else
                      $query .= "'" . htmlspecialchars($value). "',";
                }
            $query = substr_replace($query,"",-1);
            $query .= ")";

      // prepare query


      $stmt = $this->conn->prepare($query);
      if ($stmt->execute())
      {

        return true;
      }
      return false;
  }
    function dump($data){
      $json = $data->orders;


      $query = "
                CREATE TABLE " . $this->table_name ." (";


                  foreach($json[2] as $key => $value){
                      if($key=="id"){
                        $query .= $key . " BIGINT NOT NULL  PRIMARY KEY,";
                      }
                      else{
                        $query .= $key . " TEXT,";
                      }
                  }
                  $query = substr_replace($query,"",-1);
                  $query .= ")";

                  $stmt = $this->conn->prepare($query);
                  if($stmt->execute())


                  foreach ($json as $key => $value) {
                      $this->insert($value);
                  }
                  return true;



              }


}
