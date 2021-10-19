<?php 

require_once __DIR__.'/CRUDInterface.php';

class SaleItem implements CRUDInterface {

  private $sale_id;
  private $item_id;
  private $quantity;
  private $business_id;
  private $errors = [];
  private $db;
  private $sale_item_exists;


  public function __construct($params){
    $this->db = new Database();
    $this->set_attributes($params);
    if(isset($this->item_id) && isset($this->sale_id)){
      $this->sale_item_exists = True;
    }
    return $this;
  }

  // Creates a new SaleItem object. Called by the constructor. 
  // Params is ['attribute_name'=>value, 'attribute_name'=>value]
  private function set_attributes($params){
    foreach($params as $attribute_name => $attribute_value){
      $this->$attribute_name = $attribute_value;
    }
  }

  public function __get($name){
    switch($name){
      case 'sale':
        return $this->sale = Sale::find_by_id($this->sale_id);
        break;
      default:
        return $this->$name;
        break;
    }
  }

  public function __set($name, $value){
    $this->$name = $value;
  }

  public function save(){
    $has_valid_attributes = $this->has_valid_attributes();
    if($has_valid_attributes && !$this->sale_item_exists){ 
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
    }elseif($has_valid_attributes && $this->sale_item_exists){ 
      //if true then we are updating an existing customer
      return $this->update();
    }
    //If this is reached then the SaleItem object has invalid attributes. 
    return False; 
  }

  // Returns a boolean indicating whether or not the current state of the object is valid to save in the database. 
  private function has_valid_attributes(){
    if($this->quantity < 0){
      array_push($this->errors, 'Quantity must be greater than or equal to 0.');
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
      return "INSERT INTO Sales_items ( ".implode(', ', $this->get_attribute_names())." ) VALUES( $query_parameter_placeholder )";
  }

  // This function updates the record in the database. 
  private function update(){
    $params = ['sale_id'=>$this->sale_id, 'item_id'=>$this->item_id];
    $attribute_names = $this->get_attribute_names();
    foreach($attribute_names as $attribute_name){
      $params[$attribute_name] = $this->$attribute_name;
    }
    $query = $this->build_update_query($params, 'Sale_items');

    // Here I am sre-ordering the associative array of query params. 
    // sale_id and item_id need to be the last key/value pairs in the params.
    unset($params['sale_id']);
    unset($params['item_id']);
    $params['sale_id'] = $this->sale_id;
    $params['item_id'] = $this->item_id;
    
    return $this->db->execute_sql_statement($query, $params)[0];
  }

  // This function builds the update query. It needs to be refactored so that only updated values are sent to the db. 
  // This function returns a string similar to "UPDATE Sale_items SET quantity = ? WHERE sale_id = ? AND item_id = ?";
  private function build_update_query($params, $table_name){
    $attribute_names = array_keys($params);
    $query = "UPDATE $table_name SET ";
    for($i = 2; $i < count($attribute_names); $i++){
      if($i + 1 == count($params)){
        $query .= "$attribute_names[$i] = ? ";
      }else{
        $query .= "$attribute_names[$i] = ?, ";
      }
    }
    $primary_key_1 = $attribute_names[0];
    $primary_key_2 = $attribute_names[1];
    $query .= "WHERE $primary_key_1 = ? AND $primary_key_2 = ?";
    return $query;
  }

  // This function queries the database ands returns a list of attributes required for the object. 
  // Primary_key is omitted from the returned list. 
  // example return value ['first_name', 'last_name', 'etc', ... ];
  private function get_attribute_names(){
    $query = "SHOW COLUMNS FROM Sale_items";
    $results = $this->db->execute_sql_statement($query);
    $attributes = [];
    while($row = $results[1]->fetch_assoc()){
      array_push($attributes, $row['Field']);
    }
    // Omit the first two columns because this object has a composite primary key and this function is not supposed to return primary keys. 
    return array_slice($attributes, 2);
  }

  public static function all(){
    $database = new Database();
    $query = "SELECT * FROM SaleItems WHERE business_id = ?";
    $params = ['business_id' => intval($_COOKIE['business_id'])];
    $results = $database->execute_sql_statement($query, $params);
    $rows = [];
    if($results[0]){
      $results = $results[1];
      while ($sale_item_attributes = $results->fetch_assoc()) {
        array_push($rows, new SaleItem($sale_item_attributes));
      }
    }
    return $rows;
  }

  // This function finds a sale item by its id. However, a sale item is in a join table with a composite primary key. 
  // For this reason, $id is an array containing two ids. for example, $id = ['sale_id'=>23,'item_id'=>10] 
  public static function find_by_id($id){
    $sale_id = intval($id['sale_id']);
    $item_id = intval($id['item_id']);
    $db = new Database();
    $query = "SELECT * FROM Sale_items WHERE sale_id = ? AND item_id = ?";
    $params = ['sale_id' => $sale_id, 'item_id' => $item_id];
    $results = $db->execute_sql_statement($query, $params);
    if($results[0]){
      $params = $results[1]->fetch_assoc();
      return new SaleItem($params);
    }
    // There should be a custom error class. One that is thrown from the database. Not the models. 
    throw new Error("A record could not be found with 'sale_id' = $sale_id and 'item_id' = $item_id");
  }

  public function delete(){
    $query = "DELETE FROM Sale_items WHERE sale_id = ? AND item_id = ?";
    $params = ['sale_id'=>$this->sale_id, 'item_id'=>$this->item_id];
    return $this->db->execute_sql_statement($query, $params)[0];
  }
}

?>