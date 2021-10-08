<?php 

require __DIR__.'/Database.php';

class Business {

  // These could be used by Database.php in the exists() method to make using the method a lot simpler. 
  // Simply pass in the object and Database->exists() will extract the information needed to see if the Object exists. 
  // For another time. 
  // CONST TABLE_NAME = 'Businesses';
  // CONST IDENTIFIER = 'business_id';

  private $errors = [];
  private $db;
  private $business_name;
  private $business_id;
  private $employees;
  private $sales;
  private $invoices;


  public function __construct($params = NULL){
    $this->db = new Database();
    $this->set_attrs($params);
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
        extract($results[1]->fetch_assoc()); // business_id=int, business_name=String
        $params = ['business_id' => intval($business_id), 'business_name' => $business_name];
        return new Business($params);
      }
    }else{
      // There should be a custom error class. One that is thrown from the database. Not the models. 
      throw new Error("A record could not be found with 'id'=$id");
    }
  }

  // Creates a new Business object. Called by the constructor. 
  // Params is ['business_name' => String]
  private function set_attrs($params){
    extract($params);
    $this->business_name = $business_name;
    $this->business_id = isset($params['business_id']) ? $params['business_id'] : Null ;
  }

  // Saves the current object in the database. 
  public function save(){
    // Determine if the object already exists. If it does then we need to update the attributes. If we simply save 
    // the object when it already exists then this will create duplicate objects in the database. BAD. 
    $exists = False;
    //business_id will only be set when we are manipulating an object, not when we are creating a NEW object. 
    // When we are creating a NEW object, business_id is left blank so that the DB can auto increment and assign a proper id. 
    if(isset($this->business_id)){ 
      $exists = $this->db->exists(['business_id' => $this->business_id],'Businesses');
    }

    // If the object already exists in the DB then the False condition is triggered. 
    // If the object DOES NOT exists in the DB then the True condition is triggered. 
    if($this->has_valid_attributes() && !$exists){
      $query = "INSERT INTO Businesses (business_name) VALUES(?)";
      $params = ['business_name' => $this->business_name];
      $results = $this->db->execute_sql_statement($query, $params);
      //This is a boolean value. This value CAN be false is something goes wrong in the database. 
      // For this reason I don't simply return true. I return what the database returns. 
      return $results[0]; 
    }elseif($this->has_valid_attributes() && $exists){
      $this->update();
    }
    return False;
  }

  // Returns a boolean indicating whether or not the current state of the object is valid to save in the database. 
  private function has_valid_attributes(){
    $valid = False;
    $valid_name = strlen(trim($this->business_name)) > 0;
    if(!$valid_name){
      array_push($this->errors, 'You must enter a business name greater than 0 characters and less than 51 characters.');
      return $valid;
    }
    $valid = !$valid; 
    return $valid;
  }

  private function update(){
    $params = ['business_id' => $this->business_id, 'business_name' => $this->business_name];
    var_dump($this->db->update($params, 'Businesses'));
  }

  public function __get($name){
    return $this->$name;
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
      array_push($this->errors, 'You must delete all employees before deleting a business.');
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
      while($row = $rows->fetch_assoc()){
        array_push($businesses, new Business(null, $row));
      }
    }else{
      echo "Business.php line 76"; exit;
    }
    return $businesses;
  }
}
?>