<?php 

require_once __DIR__.'/CRUDInterface.php';

class SaleItem implements CRUDInterface {

  private $sale_id;
  private $item_id;
  private $item_name;
  private $item_description;
  private $in_stock;
  private $stock_level;
  private $price;

  private $sale; //This will be set to a Sale object.
  private $sale_item; 
  private $db;


  public function __construct($params){
    $this->db = new Database();
    $this->set_attributes($params);
    if(isset($this->item_id) && isset($this->sale_id)){
      $this->sale_item = True;
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

  public function save(){}

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
    throw new Error('SaleItem->delete() is not currently supported');
    // $params = ['sale_id' => $this->sale_id, 'item_id' => $this->item_id];
    // return $this->db->delete($params, 'SaleItems');
  }
}

?>