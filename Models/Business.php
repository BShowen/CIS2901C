<?php 
require_once __DIR__.'/CRUDInterface.php';

class Business implements CRUDInterface {

  // These could be used by Database.php in the exists() method to make using the method a lot simpler. 
  // Simply pass in the object and Database->exists() will extract the information needed to see if the Object exists. 
  // For another time. 
  // CONST TABLE_NAME = 'Businesses';
  // CONST IDENTIFIER = 'business_id';

  private $errors = [];
  private $db;
  private $business_name;
  private $business_id;
  private $business_exists;
  private $is_valid;

  public function __construct($params){
    $this->db = new Database();
    $this->set_attributes($params);
    $this->business_exists = isset($this->business_id); //The business_id is only set if the business exists in the database. 
    $this->is_valid = $this->has_valid_attributes();
    return $this;
  }

  // Locate a business by its id in the database. If a record is not found then this function throws an error. 
  public static function find_by_id($id){
    $db = new Database();
    $exists = $db->exists(['business_id' => $id], 'Businesses');
    if($exists){
      $query = "SELECT * FROM Businesses WHERE business_id = ?";
      $params = ['business_id' => intval($id)];
      $results = $db->execute_sql_statement($query, $params);
      if($results[0]){
        $business_attributes = $results[1]->fetch_assoc();
        return new Business($business_attributes);
      }
    }else{
      // There should be a custom error class. One that is thrown from the database. Not the models. 
      throw new Error("A record could not be found with 'id'=$id");
    }
  }

  // Creates a new Business object. Called by the constructor. 
  // Params is ['attribute_name'=>value, 'attribute_name'=>value]
  private function set_attributes($params){
    foreach($params as $attribute_name => $attribute_value){
      $this->$attribute_name = $attribute_value;
    }
  }

  // Saves the current object in the database. 
  public function save(){
    // If the object already exists in the DB then the False condition is triggered. 
    // If the object DOES NOT exists in the DB then the True condition is triggered. 
    if($this->has_valid_attributes() && !$this->business_exists){
      $query = "INSERT INTO Businesses (business_name) VALUES(?)";
      $params = ['business_name' => $this->business_name];
      $results = $this->db->execute_sql_statement($query, $params);
      $this->business_id = $this->db->last_inserted_id;
      //This is a boolean value. This value CAN be false is something goes wrong in the database. 
      // For this reason I don't simply return true. I return what the database returns. 
      return $results[0]; 
    }elseif($this->has_valid_attributes() && $this->business_exists){
      return $this->update();
    }
    return False;
  }

  // Returns a boolean indicating whether or not the current state of the object is valid to save in the database. 
  private function has_valid_attributes(){
    $this->errors = []; //Reset the list of errors. 
    if(strlen(trim($this->business_name)) == 0 || strlen(trim($this->business_name)) > 50 ){
      array_push($this->errors, 'Business name must be greater than 0 characters and less than 51 characters.');
    }
    $duplicate_business_name = $this->db->exists(['business_name' => $this->business_name], "Businesses");
    if($duplicate_business_name){
      array_push($this->errors, 'This business name is already taken.');
    }
    return count($this->errors) == 0;
  }

  private function update(){
    $params = ['business_id' => $this->business_id, 'business_name' => $this->business_name];
    return $this->db->update($params, 'Businesses')[0];
  }

  public function __get($name){
    switch($name){
      case 'customers':
        return $this->get_child_records(['table'=>'Customers']);
        break;
      case 'employees':
        return $this->get_child_records(['table'=>'Employees']);
        break;
      case 'invoices':
        return $this->get_child_records(['table'=>'Invoices']);
        break;
      case 'sales';
        return $this->get_child_records(['table'=>'Sales']);
        break;
      case 'inventory':
        return $this->get_child_records(['table'=>'Inventory_items']);
        break;
      default:
        return $this->$name;
        break;
    }
  }

  public function __set($name, $value){
    $this->$name = $value;
  }

  // Attempt to delete the record from the database. 
  // Returns a boolean. 
  // If it is an unsuccessful operation then object->errors will have error messages. 
  public function delete(){
    $business_has_employees = $this->db->exists(['business_id'=> $this->business_id], 'Employees');
    if($business_has_employees){
      array_push($this->errors, 'You cannot delete an business until the business employee\'s have been deleted.');
      return False;
    }else{
      $params = ['business_id' => $this->business_id];
      return $this->db->delete($params, 'Businesses');
    }
  }

  // Returns an array of all the Business objects. 
  public static function all(){
    $database = new Database();
    $query = "SELECT * FROM Businesses";
    $results = $database->execute_sql_statement($query);
    $businesses = [];
    if($results[0]){
      $rows = $results[1];
      while($business_attributes = $rows->fetch_assoc()){
        array_push($businesses, new Business($business_attributes));
      }
    }
    return $businesses;
  }

  // Returns the children records. 
  // $params determines what children are returned. For example, if $params = ['table'=>'Employees']
  // then a list of children Employee objects is returned. 
  private function get_child_records($params){
    $child_type = substr($params['table'], 0, strlen($params['table']) - 1);
    $child_table = $params['table'];
    $child_records = [];
    $business_has_children = $this->db->exists(['business_id'=>intval($this->business_id)], $child_table);
    if($business_has_children){
      $query = "SELECT * FROM $child_table WHERE business_id = ?";
      $params = ['business_id' => $this->business_id];
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
            case 'Inventory_item':
              $child_object = new InventoryItem($child_record_attributes);
              break;
            default:
              throw new Error("Error thrown in Sale>get_child_records>switch statement. Child type = {$child_type}");
              break;
          }
          array_push($child_records, $child_object);
        }
      }
    }else{
      array_push($this->errors, "This business does not have any $child_type"."s.");
    }
    return $child_records;  
  }

}
?>