<?php 
require __DIR__.'/../Models/Database.php';
require __DIR__.'/../Models/Sale.php';
require __DIR__.'/../Models/SaleItem.php'; //This object is used internally by Sale. This statement is required.
$db = new Database();

$sale_id =  intval($_GET['sale_id']);
$sale = Sale::find_by_id($sale_id);

$messages = ['errors'=>[], 'success'=>[]];
if($sale->delete()){
  array_push($messages['success'], 'Sale successfully deleted.');
}else{
  $messages['errors'] = $sale->errors;
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