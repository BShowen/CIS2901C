<?php 
require __DIR__.'/../Models/Invoice.php';
require __DIR__.'/../Models/Message.php';
require __DIR__.'/../globalFunctions.php';

$params = get_filtered_post_params();

foreach(array_keys($params) as $key){
  if(strlen($params[$key]) == 0){
    unset($params[$key]);
  }
}

/* 
  I call new Invoice rather than Invoice::find_by_id() because find_by_id will set every attribute in the Invoice object. I don't want that. I want to set only the attributes that are being updated. Therefor I call new Invoice() and pass in only the attributes that are passed to me by the form submission. 
*/
if(count($params) > 1){
  $invoice = new Invoice($params);
  if($invoice->update()){
    set_session_messages([new Message("success", "Invoice details have been updated.")]);
  }else{
    $messages = [];
    foreach($invoice->errors as $error_message){
      array_push($messages, new Message("error", $error_message));
    }
    set_session_messages($messages);
  }
}else{
  set_session_messages([new Message("error", "Nothing was updated.")]);
}

redirect_to("invoice", "?invoice_id={$params['invoice_id']}");
?>