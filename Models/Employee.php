<?php
require_once __DIR__.'/CRUDInterface.php';
require_once __DIR__.'/Database.php';
class Employee implements CRUDInterface {

  private $employee_id;
  private $business_id;
  private $first_name;
  private $last_name;
  private $user_name;
  private $email_address;
  private $password_digest;
  private $password_reset_token; 
  private $is_admin;
  private $password;        //This is set only when we are creating a NEW customer. This will be hashed and stored in the db. 
  private $verify_password; //This is set only when we are creating a NEW customer. This will be hashed and stored in the db. 
  private $errors = [];
  private $authenticated;
  private $db;
  // private $is_valid;
  private $temp_password;

  private $employee_exists;

  public function __construct($params){
    $this->db = new Database();
    $this->set_attributes($params);
    $this->employee_exists = isset($this->employee_id); //The employee_id is only set if the customer exists in the database. 
    // business_id will be set if the Employee object was instantiated using find_by_id. 
    // business_id will NOT be set if the Employee object is new and not yet saved in the DB. 
    // Either way, it is imperative that business_id be set. 
    if(!isset($this->business_id) && isset($_COOKIE['business_id'])){
      $this->business_id = $_COOKIE['business_id'];
    }

    // password_hash will be set if the Employee object was instantiated using find_by_id. 
    // password_hash will NOT be set if the Employee object is new and not yet saved in the DB. 
    // if(isset($this->password)){
    //   $this->password_digest = password_hash($this->password, PASSWORD_DEFAULT);
    // }

    if(!isset($this->authenticated) && isset($_COOKIE['authenticated'])){
      $this->authenticated = $_COOKIE['authenticated'];
    }

    $this->password_reset_token = '';
    if(!isset($this->temp_password)){
      $this->temp_password = '';
    }
    return $this;
  }

  public function __get($name){
    switch($name){
      case 'sales':
        return $this->get_child_records(['table'=>'Sales']);
        break;
      case 'is_valid':
        $this->is_valid = $this->has_valid_attributes();
        return $this->is_valid;
      default:
        return $this->$name;
        break;
    }
  }

  // This function will find an employee by the user_name and return the Employee object. 
  // If no match is found in the database then an invalid Employee object is returned with an error message. 
  public static function find_by_user_name($user_name){
    $db = new Database();
    $exists = $db->exists(['user_name' => $user_name], 'Employees');
    if($exists){
      $query = "SELECT * FROM Employees WHERE user_name = ?";
      $params = ['user_name' => $user_name];
      $results = $db->execute_sql_statement($query, $params);
      if($results[0]){
        $employee_attributes = $results[1]->fetch_assoc();
        return new Employee($employee_attributes);
      }
    }
    return new Employee(['errors'=>['Invalid username']]);
  }

  // returns true or false indicating if the user_name and password are correct.
  public function authenticate($password){
    $this->authenticated = password_verify($password, $this->password_digest);
    if(!$this->authenticated){
      array_push($this->errors, "Invalid password");
    }
    return $this->authenticated;
  }
  
  // Returns the children records. 
  // $params determines what children are returned. For example, if $params = ['table'=>'Employees']
  // then a list of children Employee objects is returned. 
  private function get_child_records($params){
    $child_type = substr($params['table'], 0, strlen($params['table']) - 1);
    $child_table = $params['table'];
    $child_records = [];
    $parent_has_children = $this->db->exists(['employee_id'=>intval($this->employee_id)], $child_table);
    if($parent_has_children){
      $query = "SELECT * FROM $child_table WHERE employee_id = ?";
      $params = ['employee_id' => $this->employee_id];
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
              throw new Error('Error thrown in Employee>get_child_records>switch statement.');
              break;
          }
          array_push($child_records, $child_object);
        }
      }
    }else{
      array_push($this->errors, "This employee does not have any $child_type"."s.");
    }
    return $child_records;  
  }

  public function __set($name, $value){
    $this->$name = $value;
  }

  // Params is ['attribute_name'=>value, 'attribute_name'=>value]
  private function set_attributes($params){
    if(isset($params)){
      foreach($params as $attribute_name => $attribute_value){
        $this->$attribute_name = $attribute_value;
      }
    }
  }

  // Returns an array os Employee objects. 
  public static function all(){
    $database = new Database();
    $query = "SELECT * FROM Employees WHERE business_id = ?";
    $params = ['business_id'=>intval($_COOKIE['business_id'])];
    $results = $database->execute_sql_statement($query, $params);
    $employees = [];
    if($results[0]){
      $rows = $results[1];
      while($employee_attributes = $rows->fetch_assoc()){
        array_push($employees, new Employee($employee_attributes));
      }
    }
    return $employees;
  }
  // Locate an employee by its id in the database. If a record is not found then this function throws an error. 
  public static function find_by_id($id){
    $db = new Database();
    $exists = $db->exists(['employee_id' => $id], 'Employees');
    if($exists){
      $query = "SELECT * FROM Employees WHERE employee_id = ?";
      $params = ['employee_id' => intval($id)];
      $results = $db->execute_sql_statement($query, $params);
      if($results[0]){
        $employee_attributes = $results[1]->fetch_assoc();
        return new Employee($employee_attributes);
      }
    }else{
      // There should be a custom error class. One that is thrown from the database. Not the models. 
      throw new Error("A record could not be found with 'id'=$id");
    }
  }

  // Attempts to save the current object to the database. Returns a boolean value. 
  public function save(){
    $has_valid_attributes = $this->has_valid_attributes();
    if($has_valid_attributes && !$this->employee_exists){ 
      // Create the password digest before saving the Employee. We know that $this->password is set because 
      // $this->has_valid_attributes would have returned false otherwise. 
      $this->password_digest = password_hash($this->password, PASSWORD_DEFAULT);
      //if true then we are saving a new employee
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
      $this->employee_id = $this->db->last_inserted_id;
      return $results[0]; 
      //This is a boolean value. This value CAN be false is something goes wrong in the database. 
      // For this reason I don't simply return true. I return what the database returns. 
    }elseif($has_valid_attributes && $this->employee_exists){ 
      //if true then we are updating an existing employee
      return $this->update();
    }
    //If this is reached then the employee object has invalid attributes. 
    return False; 
  }

  // This function builds an insertion query string. Called by $this->save();
  private function build_insertion_query(){
    $attribute_names = $this->get_attribute_names();   
      $query_parameter_placeholder = "";
      for($i = 0; $i < count($attribute_names); $i++){
        $query_parameter_placeholder .= ($i == count($attribute_names) - 1) ? "?" : "?, ";
      }
      return "INSERT INTO Employees ( ".implode(', ', $this->get_attribute_names())." ) VALUES( $query_parameter_placeholder )";
  }

  // This function queries the database ands returns a list of attributes required for the object. 
  // Primary_key is omitted from the returned list. 
  // example return value ['first_name', 'last_name', 'etc', ... ];
  private function get_attribute_names(){
    $query = "SHOW COLUMNS FROM Employees";
    $results = $this->db->execute_sql_statement($query);
    $attributes = [];
    while($row = $results[1]->fetch_assoc()){
      array_push($attributes, $row['Field']);
    }
    return array_slice($attributes, 1);
  }

  // Returns a boolean indicating whether or not the current state of the object is valid to save in the database. 
  private function has_valid_attributes(){
    $this->errors = []; //Reset the errors

    if(strlen(trim($this->first_name)) == 0 || strlen(trim($this->first_name)) > 20){
      array_push($this->errors, "Employee first name must be greater than 0 characters and less than 21 characters.");
    }

    if(strlen(trim($this->last_name)) == 0 || strlen(trim($this->last_name)) > 20){
      array_push($this->errors, "Employee last name must be greater than 0 characters and less than 21 characters.");
    }

    if(strlen(trim($this->user_name)) == 0 || strlen(trim($this->user_name)) > 50){
      array_push($this->errors, "Employee user name must be greater than 0 characters and less than 21 characters.");
    }elseif($this->db->exists(['user_name'=>$this->user_name], 'Employees')){
      array_push($this->errors, "This user name is already in use.");
    }

    if(strlen(trim($this->email_address)) == 0 || strlen(trim($this->email_address)) > 20){
      array_push($this->errors, "Employee email address must be greater than 0 characters and less than 51 characters.");
    }elseif($this->db->exists(['email_address'=>$this->email_address], 'Employees')){
      array_push($this->errors, "This email address is already in use.");
    } 
    
    // if(!isset($this->password_digest) && (strlen(trim($this->password)) == 0 || strlen(trim($this->password)) > 20)){
    if(strlen(trim($this->password)) == 0 || strlen(trim($this->password)) > 20){
      array_push($this->errors, "Employee password must be greater than 0 characters and less than 21 characters.");
    }
    // echo "password checked";var_dump($this->errors);exit;
    // If the business_id is not set then that means we are creating a new user and we must perform checks on the password.
    // If the business_id is set then this check will be skipped. There is no need to check the password. 
    if(!isset($this->business_id)){
      if(strlen(trim($this->password)) == 0 && strlen(trim($this->temp_password) == 0)){
        array_push($this->errors, "Password is required.");
      }elseif($this->password != $this->verify_password){
        array_push($this->errors, "Passwords do not match.");
      } 
    }

    return count($this->errors) == 0;
  }

  private function update(){
    $params = ['employee_id'=>$this->employee_id];
    $attribute_names = $this->get_attribute_names();
    foreach($attribute_names as $attribute_name){
      $params[$attribute_name] = $this->$attribute_name;
    }
    return $this->db->update($params, 'Employees')[0];
  }


  public function delete(){
    $employee_has_sales = $this->db->exists(['employee_id'=> $this->employee_id], 'Sales');
    if($employee_has_sales){
      array_push($this->errors, 'You cannot delete an employee until the employee\'s sales have been deleted.');
    }
    if(count($this->errors) == 0){
      $params = ['employee_id' => $this->employee_id];
      return $this->db->delete($params, 'Employees');
    }
    return count($this->errors) == 0;
  }
}
?>