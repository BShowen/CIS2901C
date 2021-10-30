<?php 

require_once __DIR__.'/CRUDInterface.php';
require_once __DIR__.'/Database.php';
class Customer implements CRUDInterface {
  private $business_id;
  private $customer_id;
  private $first_name;
  private $last_name; 
  private $street_address; 
  private $city;
  private $state;
  private $zip;
  private $db;

  private $errors = [];
  private $customer_exists; //True when customer exists in the database. 

  public function __construct($params){
    $this->db = new Database();
    $this->set_attributes($params);
    $this->customer_exists = isset($this->customer_id); //The customer_id is only set if the customer exists in the database. 
    if(!isset($this->business_id) && isset($_COOKIE['business_id'])){
      $this->business_id = $_COOKIE['business_id'];
    }
    return $this;
  }

  public function __set($name, $value){
    $this->$name = $value;
  }

  public static function all(){
    $database = new Database();
    $query = "SELECT * FROM Customers WHERE business_id = ?";
    $params = ['business_id'=>intval($_COOKIE['business_id'])];
    $results = $database->execute_sql_statement($query, $params);
    $customers = [];
    if($results[0]){
      $rows = $results[1];
      while($attributes = $rows->fetch_assoc()){
        array_push($customers, new Customer($attributes));
      }
    }
    return $customers;
  }

  public function delete(){
    $customer_has_sales = $this->db->exists(['customer_id'=> $this->customer_id], 'Sales');
    if($customer_has_sales){
      array_push($this->errors, 'You must delete all sales associated with this customer before deleting the customer.');
    }
    if(count($this->errors) == 0){
      $params = ['customer_id' => $this->customer_id];
      return $this->db->delete($params, 'Customers');
    }
    return False;
  }

  // Locate a business by its id in the database. If a record is not found then this function throws an error. 
  public static function find_by_id($id){
    $db = new Database();
    $exists = $db->exists(['customer_id' => $id], 'Customers');
    if($exists){
      $query = "SELECT * FROM Customers WHERE customer_id = ?";
      $params = ['customer_id' => intval($id)];
      $results = $db->execute_sql_statement($query, $params);
      if($results[0]){
        $params = $results[1]->fetch_assoc();
        return new Customer($params);

      }
    }else{
      // There should be a custom error class. One that is thrown from the database. Not the models. 
      throw new Error("A record could not be found with 'id'=$id");
    }
  }

  // Creates a new Customer object. Called by the constructor. 
  // Params is ['attribute_name'=>value, 'attribute_name'=>value]
  private function set_attributes($params){
    foreach($params as $attribute_name => $attribute_value){
      $this->$attribute_name = $attribute_value;
    }
  }

  // Saves the current object in the database. 
  public function save(){
    $has_valid_attributes = $this->has_valid_attributes();
    if($has_valid_attributes && !$this->customer_exists){ 
      //if true then we are saving a new customer
      $query = $this->build_insertion_query();

      /////////////////////////////////////////////////////////////////////////////////////////////////////////
      // This needs to be its own function
      $attribute_name_list = $this->get_attribute_names();
      $params = [];
      foreach($attribute_name_list as $attribute_name){
        $params[$attribute_name] = $this->$attribute_name;
      }
      /////////////////////////////////////////////////////////////////////////////////////////////////////////
      $results = $this->db->execute_sql_statement($query, $params);
      return $results[0]; 
      //This is a boolean value. This value CAN be false is something goes wrong in the database. 
      // For this reason I don't simply return true. I return what the database returns. 
    }elseif($has_valid_attributes && $this->customer_exists){ 
      //if true then we are updating an existing customer
      return $this->update();
    }
    //If this is reached then the customer object has invalid attributes. 
    return False; 
  }

  // This function builds an insertion query string. Called by $this->save();
  private function build_insertion_query(){
    $attribute_names = $this->get_attribute_names();   
      $query_parameter_placeholder = "";
      for($i = 0; $i < count($attribute_names); $i++){
        $query_parameter_placeholder .= ($i == count($attribute_names) - 1) ? "?" : "?, ";
      }
      return "INSERT INTO Customers ( ".implode(', ', $this->get_attribute_names())." ) VALUES( $query_parameter_placeholder )";
  }

  // This function queries the database ands returns a list of attributes required for the object. 
  // Primary_key is omitted from the returned list. 
  // example return value ['first_name', 'last_name', 'etc', ... ];
  private function get_attribute_names(){
    $query = "SHOW COLUMNS FROM Customers";
    $results = $this->db->execute_sql_statement($query);
    $attributes = [];
    while($row = $results[1]->fetch_assoc()){
      array_push($attributes, $row['Field']);
    }
    return array_slice($attributes, 1);
  }

  // Returns a boolean indicating whether or not the current state of the object is valid to save in the database. 
  private function has_valid_attributes(){
    if(strlen(trim($this->first_name)) == 0 || strlen(trim($this->first_name)) > 20){
      array_push($this->errors, "Customer first name must be greater than 0 characters and less than 21 characters.");
    }

    if(strlen(trim($this->last_name)) == 0 || strlen(trim($this->last_name)) > 20){
      array_push($this->errors, "Customer last name must be greater than 0 characters and less than 21 characters.");
    }

    if(strlen(trim($this->street_address)) == 0 || strlen(trim($this->street_address)) > 50){
      array_push($this->errors, "Customer street address must be greater than 0 characters and less than 51 characters.");
    }

    if(strlen(trim($this->city)) == 0 || strlen(trim($this->city)) > 20){
      array_push($this->errors, "Customer city must be greater than 0 characters and less than 51 characters.");
    }

    if(strlen(trim($this->state)) == 0 || strlen(trim($this->state)) > 2){
      array_push($this->errors, "Customer state must be 2 characters long.");
    }

    if(strlen(trim($this->zip)) == 0 || strlen(trim($this->zip)) > 5){
      array_push($this->errors, "Customer zip code must be greater than 0 characters and less than 6 characters.");
    }

    return count($this->errors) == 0;
  }

  private function update(){
    $params = ['customer_id'=>$this->customer_id];
    $attribute_names = $this->get_attribute_names();
    foreach($attribute_names as $attribute_name){
      $params[$attribute_name] = $this->$attribute_name;
    }
    return $this->db->update($params, 'Customers')[0];
  }


  public function __get($name){
    
    switch($name){
      case 'sales':
        return $this->get_child_records(['table'=>'Sales']);
        break;
      case 'invoices':
        return $this->get_child_records(['table'=>'Invoices']);
        break;
      default:
        return $this->$name;
        break;
    }
  }
  


  // Returns the children records. 
  // $params determines what children are returned. For example, if $params = ['table'=>'Employees']
  // then a list of children Employee objects is returned. 
  private function get_child_records($params){
    $child_type = substr($params['table'], 0, strlen($params['table']) - 1);
    $child_table = $params['table'];
    $child_records = [];
    $parent_has_children = $this->db->exists(['customer_id'=>intval($this->customer_id)], $child_table);
    if($parent_has_children){
      $query = "SELECT * FROM $child_table WHERE customer_id = ?";
      $params = ['customer_id' => $this->customer_id];
      $results = $this->db->execute_sql_statement($query, $params);
      if($results[0]){
        $rows = $results[1];
        while($child_record_attributes = $rows->fetch_assoc()){
          switch($child_type){
            case 'Employee':
              $child_object = new Employee($child_record_attributes);
              break;
            case 'Customer':
              $child_object = new Customer($child_record_attributes);
              break;
            case 'Sale':
              $child_object = new Sale($child_record_attributes);
              break;
            case 'Invoice': 
              $child_object = new Invoice($child_record_attributes);
              break;
            default:
              throw new Error('Error thrown in Customer>get_child_records>switch statement.');
              break;
          }
          array_push($child_records, $child_object);
        }
      }
    }else{
      array_push($this->errors, "This customer does not have any $child_type"."s.");
    }
    return $child_records;  
  }

}
?>