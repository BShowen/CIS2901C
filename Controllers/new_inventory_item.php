<?php
require __DIR__.'/../globalFunctions.php';
require __DIR__.'/../Models/Message.php';
require __DIR__.'/../Models/InventoryItem.php';

$params = get_filtered_post_params();
// $params['in_stock'] = intval($params['in_stock']);
$params['stock_level'] = intval($params['stock_level']);
$params['price'] = floatval($params['price']);

$inventory_item = new InventoryItem($params);
$messages = [];
if($inventory_item->save()){
  array_push($messages, new Message("success", "Item successfully added") );
  table_has_new_row(true);
}else{
  foreach($inventory_item->errors as $error_message){
    array_push($messages, new Message("error", $error_message) );
  }
}
set_session_messages($messages);
redirect_to("inventory");
?>