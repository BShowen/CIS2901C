<?php
require __DIR__.'/../Models/Database.php';
require __DIR__.'/../Models/InventoryItem.php';
$db = new Database();

$params = $_POST;
$params['item_name'] = htmlspecialchars($params['item_name']);
$params['item_description'] = htmlspecialchars($params['item_description']);
$params['in_stock'] = intval($params['in_stock']);
$params['stock_level'] = intval($params['stock_level']);
$params['price'] = floatval($params['price']);

$inventory_item = new InventoryItem($params);
$messages = ['errors'=>[], 'success'=>[]];
if($inventory_item->save()){
  array_push($messages['success'], 'Item successfully added');
  $_SESSION['messages'] = $messages;
}else{
  foreach($inventory_item->errors as $message){
    array_push($messages['errors'], $message);
  }
  $_SESSION['messages'] = $messages;
}

Header('Location: http://'.$_SERVER['HTTP_HOST'].'/businessManager/Views/inventory.php');
?>