<?php 
require __DIR__.'/../globalFunctions.php';
require __DIR__.'/../Models/Message.php';
require __DIR__.'/../Models/Sale.php';
require __DIR__.'/../Models/SaleItem.php'; //This object is used internally by Sale. This statement is required.

$sale_id =  intval($_GET['sale_id']);
$sale = Sale::find_by_id($sale_id);

$messages = [];
if($sale->delete()){
  array_push( $messages, new Message("success", "Sale successfully deleted.") );
}else{
  $errors = $sale->errors;
  foreach($errors as $error_message){
    array_push( $messages, new Message("error", $error_message) );
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