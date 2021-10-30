<?php
require __DIR__.'/../Models/Message.php';
require __DIR__.'/../globalFunctions.php';
require __DIR__.'/../Models/Invoice.php';

$invoice = Invoice::find_by_id($_GET['invoice_id']);

$messages = [];
if($invoice->delete()){
  array_push($messages, new Message("success", "Invoice successfully deleted."));
}else{
 foreach($invoice->errors as $error_message){
   array_push($messages, new Message("error", $error_message));
 }
}
set_session_messages($messages);

// Refer the user back to the page they were on. 
$referer = $_SERVER['HTTP_REFERER'];
$second_query_param = strpos($referer, "&");
if($second_query_param > 0){
  $referer = trim(substr($referer, 0, $second_query_param));
}
Header('Location: '.$referer);
?>