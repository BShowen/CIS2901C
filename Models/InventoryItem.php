<?php 

require_once __DIR__.'/CRUDInterface.php';

class InventoryItem implements CRUDInterface {

  private $item_id;
  private $item_name;
  private $item_description;
  private $in_stock;
  private $stock_level; 
  private $price;
  private $business_id;
  private $inventory_item_exists; 
  private $errors = [];
  private $db;

  public function __construct($params){
    $this->db = new Database();
    $this->set_attributes($params);
    $this->inventory_item_exists = isset($this->item_id);
    if(!isset($this->business_id) && isset($_COOKIE['business_id'])){
      $this->business_id = $_COOKIE['business_id'];
    }
    return $this;
  }

  // Creates a new InventoryItem object. Called by the constructor. 
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
    return $this->$name;
  }

  public static function all(){
    $database = new Database();
    $query = "SELECT * FROM Inventory_items where business_id = ?";
    $business_id = $_COOKIE['business_id'];
    $params = ['business_id' => $business_id];
    $results = $database->execute_sql_statement($query, $params);
    $inventory = [];
    if($results[0]){
      $rows = $results[1];
      while($attributes = $rows->fetch_assoc()){
        array_push($inventory, new InventoryItem($attributes));
      }
    }
    return $inventory;
  }

  public static function find_by_id($id){
    $db = new Database();
    $exists = $db->exists(['item_id' => $id], 'Inventory_items');
    if($exists){
      $query = "SELECT * FROM Inventory_items WHERE item_id = ?";
      $params = ['item_id' => intval($id)];
      $results = $db->execute_sql_statement($query, $params);
      if($results[0]){
        $params = $results[1]->fetch_assoc();
        return new InventoryItem($params);
      }
    }else{
      // There should be a custom error class. One that is thrown from the database. Not the models. 
      throw new Error("A record could not be found with 'id'=$id");
    }
  }

  public function save(){
    $has_valid_attributes = $this->has_valid_attributes();
    if($has_valid_attributes && !$this->inventory_item_exists){ 
      //if true then we are saving a new Item
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
    }elseif($has_valid_attributes && $this->inventory_item_exists){ 
      //if true then we are updating an existing item
      return $this->update();
    }
    //If this is reached then the sale object has invalid attributes. 
    return False; 
  }

  private function update(){
    $params = ['item_id'=>$this->item_id];
    $attribute_names = $this->get_attribute_names();
    foreach($attribute_names as $attribute_name){
      $params[$attribute_name] = $this->$attribute_name;
    }
    return $this->db->update($params, 'Inventory_items')[0];
  }

  // This function queries the database ands returns a list of attributes required for the object. 
  // Primary_key is omitted from the returned list. 
  // example return value ['first_name', 'last_name', 'etc', ... ];
  private function get_attribute_names(){
    $query = "SHOW COLUMNS FROM Inventory_items";
    $results = $this->db->execute_sql_statement($query);
    $attributes = [];
    while($row = $results[1]->fetch_assoc()){
      array_push($attributes, $row['Field']);
    }
    return array_slice($attributes, 1);
  }

  // Returns a boolean indicating whether or not the current state of the object is valid to save in the database. 
  private function has_valid_attributes(){
    if(strlen(trim($this->item_name)) == 0 || strlen(trim($this->item_name)) > 20){
      array_push($this->errors, "Item name must be greater than 0 character and less than 21 characters.");
    }
    if(strlen(trim($this->item_description)) == 0){
      array_push($this->errors, "Item description must be greater than 0 characters.");
    }
    if(!is_numeric($this->stock_level) || strlen(trim($this->stock_level)) < 0){
      array_push($this->errors, "Stock level must be greater than 0 or equal to 0.");
    }
    if(!is_numeric($this->price) || floatval($this->price) < 0){
      array_push($this->errors, "Price must be a float, greater than or equal to 0.00");
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
      return "INSERT INTO Inventory_items ( ".implode(', ', $this->get_attribute_names())." ) VALUES( $query_parameter_placeholder )";
  }

  public function delete(){
    $belongs_to_a_sale = $this->db->exists(['item_id'=> $this->item_id], 'Sale_items');
    if($belongs_to_a_sale){
      array_push($this->errors, 'This item is part of 1 or more sales. You must delete the sale first.');
    }
    if(count($this->errors) == 0){
      $params = ['item_id' => $this->item_id];
      return $this->db->delete($params, 'Inventory_items');
    }
    return False;
  }

}

?>