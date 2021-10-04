<?php 
class Invoices {
  private $database;
  
  public function __construct(){
    $this->database = new Database();
  }

  public function get_all_invoices(){
    $query = "SELECT 
              concat(C.first_name, ' ', C.last_name) AS customer_name, 
              I.sent_date, 
              I.due_date, 
              I.total, 
              I.web_link, 
              I.invoice_id
    FROM Customers C 
    JOIN Invoices I 
    USING (customer_id)";
    $results = $this->database->execute_sql_statement($query);
    $rows = [];
    if($results[0]){
      $results = $results[1];
      while ($row = $results->fetch_assoc()) {
        array_push($rows, $row);
      }
    }
    return $rows;
  }
}
?>