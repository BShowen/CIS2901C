<?php 
require __DIR__.'/../globalFunctions.php';
require __DIR__.'/../Models/Message.php';
require __DIR__.'/../Models/Invoice.php';

$params = get_filtered_post_params();
$params['customer_id'] = intval($params['customer_id']);
$params['sale_id'] = intval($params['sale_id']);
$params['total'] = doubleval($params['total']);
$params['web_link'] = "Not available right now.";
$invoice = new Invoice($params);

$messages = [];
if($invoice->save()){
  array_push( $messages, new Message("success", "Invoice successfully created") );
}else{
  $errors = $invoice->errors;
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