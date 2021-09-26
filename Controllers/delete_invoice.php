<?php
session_start();  
require __DIR__.'/../Models/Database.php';
$db = new Database();

$query = 'DELETE FROM Invoices WHERE invoice_id = ?';
$params = $_GET;
$params['invoice_id'] = intval($params['invoice_id']);

$results = $db->execute_sql_statement($query, $params);

$_SESSION['user_message'] = 'Invoice successfully deleted';
Header('Location: http://'.$_SERVER['HTTP_HOST'].'/businessManager/Views/invoices.php');

?>