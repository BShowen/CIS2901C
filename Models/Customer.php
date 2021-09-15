<?php 

// Require in the Database file. 
require_once __DIR__.'/Database.php';

class Customer {
  private $customer_id;
  private $first_name;
  private $last_name; 
  private $street_address; 
  private $city;
  private $state;
  private $zip;
  private $db;

  public function __construct($customer_id, $first_name, $last_name, $street_address, $city, $state, $zip){
    $this->db = new Database();
    $this->customer_id = $customer_id;
    $this->first_name = $first_name;
    $this->last_name = $last_name;
    $this->street_address = $street_address;
    $this->city = $city;
    $this->state = $state;
    $this->zip = $zip;
  }

  public function set_first_name($new_first_name){
    $query = "UPDATE Customer SET first_name = ? WHERE customer_id = ?";
    $params = ["first_name"=>$new_first_name, "customer_id"=>$this->customer_id];
    $this->db->execute_sql_statement($query, $params);
  }

  public function set_last_name($new_last_name){
    $query = "UPDATE Customer SET last_name = ? WHERE customer_id = ?";
    $params = ["first_name"=>$new_last_name, "customer_id"=>$this->customer_id];
    $this->db->execute_sql_statement($query, $params);
  }

  public function set_street_address($new_street_address){
    $query = "UPDATE Customer SET street_address = ? WHERE customer_id = ?";
    $params = ["first_name"=>$new_street_address, "customer_id"=>$this->customer_id];
    $this->db->execute_sql_statement($query, $params);
  }

  public function set_city($new_city){
    $query = "UPDATE Customer SET city = ? WHERE customer_id = ?";
    $params = ["first_name"=>$new_city, "customer_id"=>$this->customer_id];
    $this->db->execute_sql_statement($query, $params);
  }

  public function set_zip($new_zip){
    $query = "UPDATE Customer SET zip = ? WHERE customer_id = ?";
    $params = ["first_name"=>$new_zip, "customer_id"=>$this->customer_id];
    $this->db->execute_sql_statement($query, $params);
  }

  public function get_customer_id(){
    return $this->customer_id;
  }  

  public function get_first_name(){
    return $this->first_name;
  }

  public function get_last_name(){
    return $this->last_name;
  }

  public function get_street_address(){
    return $this->street_address;
  }

  public function get_city(){
    return $this->city;
  }

  public function get_state(){
    return $this->state;
  }

  public function get_zip(){
    return $this->zip;
  }
}
?>