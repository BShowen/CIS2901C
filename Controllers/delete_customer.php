<?php 
  session_start();  
  require __DIR__.'/../Models/Database.php';
  $db = new Database();

  $query = 'DELETE FROM Customers WHERE customer_id = ?';
  $params = $_GET;
  $params['customer_id'] = intval($params['customer_id']);
  var_dump($params);

  $results = $db->execute_sql_statement($query, $params);

  $_SESSION['user_message'] = 'Customer successfully deleted';
  Header('Location: http://'.$_SERVER['HTTP_HOST'].'/businessManager/Views/customers.php');

?>