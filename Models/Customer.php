<?php 
class Customer {
  private $customer_id;
  private $first_name;
  private $last_name; 
  private $street_address; 
  private $city;
  private $state;
  private $zip;
  private $db;

  public function __construct($customer_id){
    $this->db = new Database();
    $this->customer_id = $customer_id;
    $this->get_attributes();
  }

  private function get_attributes(){
    $query = "SELECT * FROM Customers WHERE customer_id = ?";
    $param = ['customer_id' => $this->customer_id];
    $result = $this->db->execute_sql_statement($query, $param);
    if($result[0]){
      $result = $result[1]->fetch_assoc();
      // This extracts customer_id, first_name, last_name, street_address, city, state, zip.
      extract($result); 
    }
    $this->first_name = $first_name;
    $this->last_name = $last_name; 
    $this->street_address = $street_address;
    $this->city = $city;
    $this->state = $state;
    $this->zip = $zip;
  }

  public function set_first_name($new_first_name){
    $query = "UPDATE Customer SET first_name = ? WHERE customer_id = ?";
    $params = ["first_name"=>$new_first_name, "customer_id"=>$this->customer_id];
    $this->db->execute_sql_statement($query, $params);
  }

  public function set_last_name($new_last_name){
    $query = "UPDATE Customer SET last_name = ? WHERE customer_id = ?";
    $params = ["first_name"=>$new_last_name, "customer_id"=>$this->customer_id];
    $this->db->execute_sql_statement($query, $params);
  }

  public function set_street_address($new_street_address){
    $query = "UPDATE Customer SET street_address = ? WHERE customer_id = ?";
    $params = ["first_name"=>$new_street_address, "customer_id"=>$this->customer_id];
    $this->db->execute_sql_statement($query, $params);
  }

  public function set_city($new_city){
    $query = "UPDATE Customer SET city = ? WHERE customer_id = ?";
    $params = ["first_name"=>$new_city, "customer_id"=>$this->customer_id];
    $this->db->execute_sql_statement($query, $params);
  }

  public function set_zip($new_zip){
    $query = "UPDATE Customer SET zip = ? WHERE customer_id = ?";
    $params = ["first_name"=>$new_zip, "customer_id"=>$this->customer_id];
    $this->db->execute_sql_statement($query, $params);
  }

  public function get_customer_id(){
    return $this->customer_id;
  }  

  public function get_first_name(){
    return $this->first_name;
  }

  public function get_last_name(){
    return $this->last_name;
  }

  public function get_street_address(){
    return $this->street_address;
  }

  public function get_city(){
    return $this->city;
  }

  public function get_state(){
    return $this->state;
  }

  public function get_zip(){
    return $this->zip;
  }

  // This function returns a 2 dimensional array containing the sales for the current customer. 
  // This function will return a populated array or an empty array if the customer doesn't have sales. 
  public function get_sales(){
    $query = "SELECT 
      concat(E.first_name, ' ', E.last_name) AS 'sales_person',  
      S.sale_id,
      S.sale_total, 
      S.sale_date
      FROM Sales AS S JOIN Customers AS C USING (customer_id)
      JOIN Employees AS E USING (employee_id)
      WHERE customer_id = ?";
    $result = $this->db->execute_sql_statement($query, ['customer_id' => $this->customer_id]);
    $sales = [];
    if($result[0]){
      $result = $result[1];
      while($row = $result->fetch_assoc()){
        extract($row);
        array_push($sales, ['sale_id' => $sale_id, 'sales_person' => $sales_person, 'sale_total' => $sale_total, 'sale_date' => $sale_date]);
      }
    }
    return $sales;
  }

  public function get_invoices_for_sale($sale_id){
    $query = "SELECT invoice_id, sent_date, due_date, total, web_link FROM Invoices WHERE sale_id = ?";
    $param = ['sale_id' => $sale_id];
    $result = $this->db->execute_sql_statement($query, $param);
    $invoices = [];
    if($result[0]){
      $result = $result[1];
      while($invoice = $result->fetch_assoc()){
        extract($invoice);
        array_push($invoices, ['invoice_id'=>$invoice_id, 'sent_date'=>$sent_date,'due_date'=>$due_date, 'total'=>$total, 'web_link'=>$web_link]);
      }
    }
    return $invoices;
  }

}
?>