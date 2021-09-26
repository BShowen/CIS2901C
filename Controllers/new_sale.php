<?php
  session_start();
  // connect to the database. 
  require __DIR__.'/../Models/Database.php';
  $db = new Database();
  
  $query = 'INSERT INTO Sales (customer_id, sale_total, sale_date, employee_id)
  VALUES(?,?,DATE(?),?)';
  
  $params = $_POST;
  $params['customer_id'] = intval($params['customer_id']);
  $params['sale_total'] = doubleval($params['sale_total']);
  $current_employee_id = intval($_COOKIE['current_user']);
  $params['employee_id'] = $current_employee_id;
  
  $results = $db->execute_sql_statement($query, $params);
  
  $_SESSION['user_message'] = 'Sale successfully added';
  Header('Location: http://'.$_SERVER['HTTP_HOST'].'/businessManager/Views/sales.php');
  
  // send a response back to the caller. Success for Failure. 
?>