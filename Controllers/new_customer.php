<?php 
session_start();
// connect to the database. 
require __DIR__.'/../Models/Database.php';
$db = new Database();

$query = 'INSERT INTO Customers (first_name, last_name, street_address, city, state, zip)
VALUES(?,?,?,?,?,?)';

$params = $_POST;
$params['zip'] = intval($params['zip']);

$results = $db->execute_sql_statement($query, $params);

$_SESSION['user_message'] = 'Customer successfully added';
Header('Location: http://'.$_SERVER['HTTP_HOST'].'/businessManager/Views/customers.php');

// send a response back to the caller. Success for Failure. 
?>