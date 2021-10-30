<?php 
require __DIR__.'/../globalFunctions.php';
require __DIR__.'/../Models/Message.php';
require __DIR__.'/../Models/Customer.php';

$customer_id =  intval($_GET['customer_id']);
$customer = Customer::find_by_id($customer_id);

$messages = [];
if($customer->delete()){
  array_push($messages, new Message("success", "Customer successfully deleted."));
}else{
  $errors = $customer->errors;
  foreach($errors as $error_message){
    array_push($messages, new Message("error", $error_message));
  }
}
set_session_messages($messages);
redirect_to("customers");
?>