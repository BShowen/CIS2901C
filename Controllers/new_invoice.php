<?php 
session_start();
// connect to the database. 
require __DIR__.'/../Models/Database.php';
$db = new Database();

$query = 'INSERT INTO Invoices (customer_id, sale_id, sent_date, due_date, total, web_link)
VALUES(?,?,Date(?),Date(?),?,?)';

$params = $_POST;
$params['customer_id'] = intval($params['customer_id']);
$params['sale_id'] = intval($params['sale_id']);
$params['total'] = doubleval($params['total']);
$params['web_link'] = "Not available right now.";

$results = $db->execute_sql_statement($query, $params);

$_SESSION['user_message'] = 'Invoice successfully added';
Header('Location: http://'.$_SERVER['HTTP_HOST'].'/businessManager/Views/invoices.php');
?>