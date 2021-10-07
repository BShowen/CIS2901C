<?php
session_start();

require __DIR__.'/../Models/Database.php';
$db = new Database();

$query = 'INSERT INTO Inventory_items (item_name, item_description, in_stock, stock_level, price) VALUES(?,?,?,?,?)';
$params = $_POST;
$params['item_name'] = htmlspecialchars($params['item_name']);
$params['item_description'] = htmlspecialchars($params['item_description']);
$params['in_stock'] = intval($params['in_stock']);
$params['stock_level'] = intval($params['stock_level']);
$params['price'] = floatval($params['price']);

$results = $db->execute_sql_statement($query, $params);
$_SESSION['user_message'] = 'Item successfully added';
Header('Location: http://'.$_SERVER['HTTP_HOST'].'/businessManager/Views/inventory.php');
?>