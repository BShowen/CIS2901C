<?php
session_start();

require __DIR__.'/../Models/Database.php';
$db = new Database();

$query = 'DELETE FROM Inventory_items WHERE item_id = ?';
$params = $_GET;
$params['item_id'] = intval($params['item_id']);

$results = $db->execute_sql_statement($query, $params);
$_SESSION['user_message'] = 'Item successfully deleted';
Header('Location: http://'.$_SERVER['HTTP_HOST'].'/businessManager/Views/inventory.php');
?>