<?php 
require __DIR__.'/../globalFunctions.php';
require __DIR__.'/../Models/Message.php';
require __DIR__.'/../Models/Customer.php';

$params = get_filtered_post_params();
$params['zip'] = intval($params['zip']);

$customer = new Customer($params);
$messages = [];
if($customer->save()){
  array_push($messages, new Message("success", "Customer successfully added"));
  table_has_new_row("true");
}else{
  $errors = $customer->errors;
  foreach($errors as $error_message){
    array_push($messages, new Message("error", $error_message));
  }
}
set_session_messages($messages);
redirect_to("customers");
?>