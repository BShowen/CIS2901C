<?php 

require_once __DIR__.'/CRUDInterface.php';

class Invoice implements CRUDInterface {

  private $invoice_id;
  private $customer_id; 
  private $sale_id;
  private $sent_date; 
  private $due_date;
  private $total;
  private $web_link = "";
  private $invoice_exists; 
  private $errors = [];
  private $db;

  private $customer; //This is set to a Customer object. 

  public function __construct($params){
    $this->db = new Database();
    $this->set_attributes($params);
    $this->invoice_exists = isset($this->invoice_id);
    if(!isset($this->business_id) && isset($_COOKIE['business_id'])){
      $this->business_id = intval($_COOKIE['business_id']);
    }
    return $this;
  }

  // Creates a new Invoice object. Called by the constructor. 
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
      case 'customer':
        return $this->customer = Customer::find_by_id($this->customer_id);
        break;
      default:
        return $this->$name;
        break;
    }
  }

  public static function all(){
    $database = new Database();
    $query = "SELECT * FROM Invoices WHERE business_id = ?";
    $params = ['business_id' => intval($_COOKIE['business_id'])];
    $results = $database->execute_sql_statement($query, $params);
    $rows = [];
    if($results[0]){
      $results = $results[1];
      while ($invoice_params = $results->fetch_assoc()) {
        array_push($rows, new Invoice($invoice_params));
      }
    }
    return $rows;
  }

  public static function find_by_id($id){
    $db = new Database();
    $exists = $db->exists(['invoice_id' => $id], 'Invoices');
    if($exists){
      $query = "SELECT * FROM Invoices WHERE invoice_id = ?";
      $params = ['invoice_id' => intval($id)];
      $results = $db->execute_sql_statement($query, $params);
      if($results[0]){
        $invoice_attributes = $results[1]->fetch_assoc();
        return new Invoice($invoice_attributes);

      }
    }else{
      // There should be a custom error class. One that is thrown from the database. Not the models. 
      throw new Error("A record could not be found with 'id'=$id");
    }
  }

  public function save(){
    $has_valid_attributes = $this->has_valid_attributes();
    if($has_valid_attributes && !$this->invoice_exists){ 
      //if true then we are saving a new customer
      $query = $this->build_insertion_query();
      /////////////////////////////////////////////////////////////////////////////////////////////////////////
      // This needs to be its own function
      $attribute_name_list = $this->get_attribute_names();
      $params = [];
      foreach($attribute_name_list as $attribute_name){
        $params[$attribute_name] = $this->$attribute_name;
      }
      var_dump($params);
      /////////////////////////////////////////////////////////////////////////////////////////////////////////
      $results = $this->db->execute_sql_statement($query, $params);
      return $results[0]; 
      //This is a boolean value. This value CAN be false is something goes wrong in the database. 
      // For this reason I don't simply return true. I return what the database returns. 
    }elseif($has_valid_attributes && $this->invoice_exists){ 
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
      return "INSERT INTO Invoices ( ".implode(', ', $this->get_attribute_names())." ) VALUES( $query_parameter_placeholder )";
  }

  // This function queries the database ands returns a list of attributes required for the object. 
  // Primary_key is omitted from the returned list. 
  // example return value ['first_name', 'last_name', 'etc', ... ];
  private function get_attribute_names(){
    $query = "SHOW COLUMNS FROM Invoices";
    $results = $this->db->execute_sql_statement($query);
    $attributes = [];
    while($row = $results[1]->fetch_assoc()){
      array_push($attributes, $row['Field']);
    }
    return array_slice($attributes, 1);
  }

  // Returns a boolean indicating whether or not the current state of the object is valid to save in the database. 
  private function has_valid_attributes(){
    if(!isset($this->due_date) || strlen(trim($this->due_date)) == 0){
      array_push($this->errors, "You must enter a due date.");
    }

    if(!isset($this->sent_date) || strlen(trim($this->sent_date)) == 0){
      array_push($this->errors, "You must enter a sent date.");
    }

    if(!isset($this->total) || $this->total == 0){
      array_push($this->errors, "You must enter a total greater than 0.");
    }

    if($this->sent_date > $this->due_date){
      array_push($this->errors, "The sent date must come before the due date.");
    }
    return count($this->errors) == 0;
  }

  private function update(){
    $params = ['invoice_id'=>$this->invoice_id];
    $attribute_names = $this->get_attribute_names();
    foreach($attribute_names as $attribute_name){
      $params[$attribute_name] = $this->$attribute_name;
    }
    return $this->db->update($params, 'Invoices')[0];
  }

  public function delete(){
    $params = ['invoice_id' => $this->invoice_id];
    return $this->db->delete($params, 'Invoices');
  }

}

?>