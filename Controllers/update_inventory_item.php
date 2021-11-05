<?php 
require __DIR__.'/../Models/InventoryItem.php';
require __DIR__.'/../Models/Message.php';
require __DIR__.'/../globalFunctions.php';

$params = get_filtered_post_params();

foreach(array_keys($params) as $key){
  if(strlen($params[$key]) == 0){
    unset($params[$key]);
  }
}

/* 
  I call new InventoryItem rather than InventoryItem::find_by_id() because find_by_id will set every attribute in the InventoryItem object. I don't want that. I want to set only the attributes that are being updated. Therefor I call new InventoryItem() and pass in only the attributes that are passed to me by the form submission. 
*/
if(count($params) > 1){
  $item = new InventoryItem($params);
  if($item->update()){
    set_session_messages([new Message("success", "Inventory item details have been updated.")]);
  }else{
    $messages = [];
    foreach($item->errors as $error_message){
      array_push($messages, new Message("error", $error_message));
    }
    set_session_messages($messages);
  }
}else{
  set_session_messages([new Message("error", "Nothing was updated.")]);
}



redirect_to("inventory_item", "?item_id={$params['item_id']}");
?>