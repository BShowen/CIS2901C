<?php 
session_start();

require __DIR__.'/../Models/Database.php';
$db = new Database();

$query = 'DELETE FROM Sales WHERE sale_id = ?';
$params = $_GET;
$params['sale_id'] = intval($params['sale_id']);

$results = $db->execute_sql_statement($query, $params);
$_SESSION['user_message'] = 'Sale successfully deleted';
Header('Location: http://'.$_SERVER['HTTP_HOST'].'/businessManager/Views/sales.php');
?>