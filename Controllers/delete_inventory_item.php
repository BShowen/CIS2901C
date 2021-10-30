<?php
require __DIR__.'/../globalFunctions.php';
require __DIR__.'/../Models/Message.php';
require __DIR__.'/../Models/InventoryItem.php';

$item_id = intval($_GET['item_id']);
$item = InventoryItem::find_by_id($item_id);

$messages = [];
if($item->delete()){
  array_push( $messages, new Message("success", "Item successfully deleted.") );
}else{
  $errors = $item->errors;
  foreach($errors as $error_message){
    array_push($messages, new Message("error", $error_message));
  }
}
set_session_messages($messages);
redirect_to("inventory");
?>