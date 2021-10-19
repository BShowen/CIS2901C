<?php 

require_once __DIR__.'/CRUDInterface.php';

class Sale implements CRUDInterface {

  private $sale_id;
  private $customer_id;
  private $employee_id;
  private $business_id;
  private $sale_total; 
  private $sale_date;
  private $sale_exists; 
  private $errors = [];
  private $db;

  public function __construct($params){
    $this->db = new Database();
    $this->set_attributes($params);
    $this->sale_exists = isset($this->sale_id);
    if(!isset($this->business_id) && isset($_COOKIE['business_id'])){
      $this->business_id = $_COOKIE['business_id'];
    }
    return $this;
  }

  // Creates a new Sale object. Called by the constructor. 
  // Params is ['attribute_name'=>value, 'attribute_name'=>value]
  private function set_attributes($params){
    foreach($params as $attribute_name => $attribute_value){
      $this->$attribute_name = $attribute_value;
    }
  }

  public function __set($name, $value){
    $this->$name = $value;
  }

  public function __get($name){
    switch($name){
      case 'invoices':
        return $this->get_child_records(['table'=>'Invoices']);
        break;
      case 'sale_items':
        return $this->get_child_records(['table'=>'Sale_items']);
        break;
      case 'sales_person':
        return Employee::find_by_id($this->employee_id);
        break;
      case 'customer':
        return Customer::find_by_id($this->customer_id);
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
    $parent_has_children = $this->db->exists(['sale_id'=>intval($this->sale_id)], $child_table);
    if($parent_has_children){
      $query = "SELECT * FROM $child_table WHERE sale_id = ?";
      $params = ['sale_id' => $this->sale_id];
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
            case 'Sale_item':
              $child_object = new SaleItem($child_record_attributes);
              break;
            default:
              throw new Error("Error thrown in Sale>get_child_records>switch statement. Child type = {$child_type}");
              break;
          }
          array_push($child_records, $child_object);
        }
      }
    }else{
      $child_type = strtolower($child_type);
      array_push($this->errors, "This sale does not have any $child_type"."s.");
    }
    return $child_records;  
  }

  public static function all(){
    $database = new Database();
    $query = "SELECT * FROM Sales where business_id = ?";
    $business_id = $_COOKIE['business_id'];
    $params = ['business_id' => $business_id];
    $results = $database->execute_sql_statement($query, $params);
    $sales = [];
    if($results[0]){
      $rows = $results[1];
      while($attributes = $rows->fetch_assoc()){
        array_push($sales, new Sale($attributes));
      }
    }
    return $sales;
  }

  public static function find_by_id($id){
    $db = new Database();
    $exists = $db->exists(['sale_id' => $id], 'Sales');
    if($exists){
      $query = "SELECT * FROM Sales WHERE sale_id = ?";
      $params = ['sale_id' => intval($id)];
      $results = $db->execute_sql_statement($query, $params);
      if($results[0]){
        $params = $results[1]->fetch_assoc();
        return new Sale($params);
      }
    }else{
      // There should be a custom error class. One that is thrown from the database. Not the models. 
      throw new Error("A record could not be found with 'id'=$id");
    }
  }

  public function save(){
    $has_valid_attributes = $this->has_valid_attributes();
    if($has_valid_attributes && !$this->sale_exists){ 
      //if true then we are saving a new Sale
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
    }elseif($has_valid_attributes && $this->sale_exists){ 
      //if true then we are updating an existing sale
      return $this->update();
    }
    //If this is reached then the sale object has invalid attributes. 
    return False; 
  }

  private function update(){
    $params = ['sale_id'=>$this->sale_id];
    $attribute_names = $this->get_attribute_names();
    foreach($attribute_names as $attribute_name){
      $params[$attribute_name] = $this->$attribute_name;
    }
    return $this->db->update($params, 'Sales')[0];
  }

  // This function queries the database ands returns a list of attributes required for the object. 
  // Primary_key is omitted from the returned list. 
  // example return value ['first_name', 'last_name', 'etc', ... ];
  private function get_attribute_names(){
    $query = "SHOW COLUMNS FROM Sales";
    $results = $this->db->execute_sql_statement($query);
    $attributes = [];
    while($row = $results[1]->fetch_assoc()){
      array_push($attributes, $row['Field']);
    }
    return array_slice($attributes, 1);
  }

  // Returns a boolean indicating whether or not the current state of the object is valid to save in the database. 
  private function has_valid_attributes(){
    if($this->sale_total <= 0){
      array_push($this->errors, 'A sale must have a total greater than 0.');
    }
    if(strlen(trim($this->sale_date)) == 0){
      array_push($this->errors, 'A sale date is required for a sale.');
    }
    return count($this->errors) == 0;
  }

  // This function builds an insertion query string. Called by $this->save();
  private function build_insertion_query(){
    $attribute_names = $this->get_attribute_names();   
      $query_parameter_placeholder = "";
      for($i = 0; $i < count($attribute_names); $i++){
        $query_parameter_placeholder .= ($i == count($attribute_names) - 1) ? "?" : "?, ";
      }
      return "INSERT INTO Sales ( ".implode(', ', $this->get_attribute_names())." ) VALUES( $query_parameter_placeholder )";
  }

  public function delete(){
    $sale_has_invoices = $this->db->exists(['sale_id'=> $this->sale_id], 'Invoices');
    if($sale_has_invoices){
      array_push($this->errors, 'You cannot delete a sale until the sale\'s invoices have been deleted.');
    }
    if(count($this->errors) == 0){
      $sale_items = $this->sale_items;
      foreach($sale_items as $sale_item){
        $sale_item->delete();
      }
      $params = ['sale_id' => $this->sale_id];
      return $this->db->delete($params, 'Sales');
    }
    return False;
  }

}

?>