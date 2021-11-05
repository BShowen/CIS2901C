<?php
require_once __DIR__.'/CRUDInterface.php';
require_once __DIR__.'/Database.php';
class Employee implements CRUDInterface {

  // These are the attributes that you will find in the database. 
  private $employee_id;
  private $business_id;
  private $first_name;
  private $last_name;
  private $user_name;
  private $email_address;
  private $password_digest;
  private $password_reset_token; 
  private $is_admin;
  private $temp_password;
  
  // These are attributes that are used for business logic. 
  private $password;        //This is set only when we are creating a NEW customer. This will be hashed and stored in the db. 
  private $verify_password; //This is set only when we are creating a NEW customer. This will be hashed and stored in the db. 
  private $errors = [];
  private $db;

  public function __construct($params){
    $this->db = new Database();
    $this->set_attributes($params);
    return $this;
  }

  
  public function __get($name){
    switch($name){
      case 'sales':
        return $this->get_child_records(['table'=>'Sales']);
        break;
      case 'first_name':
        return ucfirst($this->first_name);
        break;
      case 'last_name':
        return ucfirst($this->last_name);
        break;
      default:
        return $this->$name;
        break;
    }
  }

  // This function will find an employee by the user_name and return the Employee object. 
  // If no match is found in the database then an Error is thrown.  
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
    throw new Error("Invalid username");
    // return new Employee(['errors'=>['Invalid username']]);
  }

  // returns true or false indicating if the user_name and password are correct.
  public function authenticate($password){
    if(password_verify($password, $this->password_digest)){
      return true;
    }else{
      throw new Error("Invalid password");
    }
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

  // Params is ['attribute_name'=>value, 'attribute_name'=>value]
  private function set_attributes($params){
    if(isset($params)){
      // This loop will set the attributes that were passed in. 
      foreach($params as $attribute_name => $attribute_value){
        if($attribute_name == 'first_name' || $attribute_name == 'last_name' ){
          $this->$attribute_name = strtolower($attribute_value);
        }else{
          $this->$attribute_name = $attribute_value;
        }
      }
      
      // Any attributes that were not set will now be set to their default values.   

      // The business_id is needs to be set. 
      if(!isset($this->business_id) && isset($_COOKIE['business_id'])){
        $this->business_id = $_COOKIE['business_id'];
      }

      $this->password_reset_token = '';
      if(!isset($this->temp_password)){
        $this->temp_password = '';
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
    if( $this->can_save() ){ 
      /* 
      Create the password digest before saving the Employee. We know that $this->password is set because 
      $this->can_save would have returned false. 
      */
      $this->password_digest = password_hash($this->password, PASSWORD_DEFAULT);
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
      //This is a boolean value. This value CAN be false is something goes wrong in the database. 
      // For this reason I don't simply return true. I return what the database returns. 
      return $results[0]; 
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

  /*
  This function queries the database ands returns a list of attributes required for the object. 
  Primary_key is omitted from the returned list. 
  example return value ['first_name', 'last_name', 'etc', ... ];
  */
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

    if(strlen(trim($this->email_address)) == 0 || strlen(trim($this->email_address)) > 50){
      array_push($this->errors, "Employee email address must be greater than 0 characters and less than 51 characters.");
    }elseif($this->db->exists(['email_address'=>$this->email_address], 'Employees')){
      array_push($this->errors, "This email address is already in use.");
    } 
    

    if(strlen(trim($this->password)) == 0 || strlen(trim($this->password)) > 20){
      array_push($this->errors, "Employee password must be greater than 0 characters and less than 21 characters.");
    }

    /*
    If the business_id is not set then that means we are creating a new user and we must perform checks on the password.
    If the business_id is set then this check will be skipped. There is no need to check the password. 
    */
    if(!isset($this->business_id)){
      if(strlen(trim($this->password)) == 0 && strlen(trim($this->temp_password) == 0)){
        array_push($this->errors, "Password is required.");
      }elseif($this->password != $this->verify_password){
        array_push($this->errors, "Passwords do not match.");
      } 
    }

    return count($this->errors) == 0;
  }

  public function update(){
    if($this->can_update()){
      $params = ['employee_id'=>$this->employee_id];
      $attribute_names = $this->get_attribute_names();
      foreach($attribute_names as $attribute_name){
        if($this->$attribute_name != null){
          $params[$attribute_name] = $this->$attribute_name;
        }
      }
      unset($params['business_id']);
      return $this->db->update($params, 'Employees')[0];  
    }
    return false;
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

  /* 
  This function validates only the attributes that the object currently has set. For example, if $this->name is set and $this->age is not set, then this function makes sure to validate only $this->name and not $this->age. 
  */
  private function can_update(){
    $this->errors = [];
    $attribute_names = $this->get_attribute_names();
    foreach($attribute_names as $attribute_name){
      if(isset($this->$attribute_name)){
        $this->validate_attribute($attribute_name);
      }
    }
    return count($this->errors) == 0;
  }

  // Returns a boolean indicating whether or not the current state of the object is valid to save in the database. 
  private function can_save(){
    $this->errors = [];
    $attribute_names = $this->get_attribute_names();
    foreach($attribute_names as $attribute_name){
      $this->validate_attribute($attribute_name);
    }
    return count($this->errors) == 0;
  }

  /*
  This function will validate any attribute that you ask it to. $attribute_name equals a string representation of what attribute to check. For example, validate_attribute("first_name"); 
  */
  private function validate_attribute($attribute){
    switch( strtolower($attribute) ){
      case 'first_name':
        if(strlen(trim($this->first_name)) == 0 || strlen(trim($this->first_name)) > 20){
          array_push($this->errors, "Employee first name must be greater than 0 characters and less than 21 characters.");
        }
        break;
      case 'last_name':
        if(strlen(trim($this->last_name)) == 0 || strlen(trim($this->last_name)) > 20){
          array_push($this->errors, "Employee last name must be greater than 0 characters and less than 21 characters.");
        }
        break;
      case 'user_name':
        if(strlen(trim($this->user_name)) == 0 || strlen(trim($this->user_name)) > 50){
          array_push($this->errors, "Employee user name must be greater than 0 characters and less than 21 characters.");
        }elseif($this->db->exists(['user_name'=>$this->user_name], 'Employees')){
          array_push($this->errors, "This user name is already in use.");
        }
        break;
      case 'email_address':
        if(strlen(trim($this->email_address)) == 0 || strlen(trim($this->email_address)) > 50){
          array_push($this->errors, "Employee email address must be greater than 0 characters and less than 51 characters.");
        }elseif($this->db->exists(['email_address'=>$this->email_address], 'Employees')){
          array_push($this->errors, "This email address is already in use.");
        } 
        break;
      case 'password':
        if(strlen(trim($this->password)) == 0 || strlen(trim($this->password)) > 20){
          array_push($this->errors, "Employee password must be greater than 0 characters and less than 21 characters.");
        }
        // If the business_id is not set then that means we are creating a new user and we must perform checks on the password.
        // If the business_id is set then this check will be skipped. There is no need to check the password. 
        if(!isset($this->business_id)){
          if(strlen(trim($this->password)) == 0 && strlen(trim($this->temp_password) == 0)){
            array_push($this->errors, "Password is required.");
          }elseif($this->password != $this->verify_password){
            array_push($this->errors, "Passwords do not match.");
          } 
        }
        break;
      default:
        break;
    }
  }
}
?>