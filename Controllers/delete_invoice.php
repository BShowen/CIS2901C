<?php
require __DIR__.'/../Models/Database.php';
require __DIR__.'/../Models/Invoice.php';
$db = new Database();

$invoice = Invoice::find_by_id($_GET['invoice_id']);

$messages = ['errors'=>[], 'success'=>[]];
if($invoice->delete()){
  array_push($messages['success'], 'Invoice successfully deleted.');
}else{
  $messages['errors'] = $invoice->errors;
}
$_SESSION['messages'] = $messages;

// Refer the user back to the page they were on. 
$referer = $_SERVER['HTTP_REFERER'];
$second_query_param = strpos($referer, "&");
if($second_query_param > 0){
  $referer = trim(substr($referer, 0, $second_query_param));
}
Header('Location: '.$referer);
?>