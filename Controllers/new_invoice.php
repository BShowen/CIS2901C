<?php 
require __DIR__.'/../Models/Database.php';
require __DIR__.'/../Models/Invoice.php';
$db = new Database();

$params = $_POST;
$params['customer_id'] = intval($params['customer_id']);
$params['sale_id'] = intval($params['sale_id']);
$params['total'] = doubleval($params['total']);
$params['web_link'] = "Not available right now.";
$invoice = new Invoice($params);

$messages = ['errors'=>[], 'success'=>[]];
if($invoice->save()){
  array_push($messages['success'], 'Invoice successfully created');
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