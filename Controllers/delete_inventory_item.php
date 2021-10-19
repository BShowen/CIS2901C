<?php
require __DIR__.'/../Models/Database.php';
require __DIR__.'/../Models/InventoryItem.php';
$db = new Database();

$item_id = intval($_GET['item_id']);
$item = InventoryItem::find_by_id($item_id);

$messages = ['errors'=>[], 'success'=>[]];
if($item->delete()){
  array_push($messages['success'], 'Item successfully deleted.');
}else{
  $messages['errors'] = $item->errors;
}
$_SESSION['messages'] = $messages;

Header('Location: http://'.$_SERVER['HTTP_HOST'].'/businessManager/Views/inventory.php');
?>