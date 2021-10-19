<?php 
require __DIR__.'/../Models/Database.php';
require __DIR__.'/../Models/Customer.php';
$db = new Database();

$customer_id =  intval($_GET['customer_id']);
$customer = Customer::find_by_id($customer_id);

$messages = ['errors'=>[], 'success'=>[]];
if($customer->delete()){
  array_push($messages['success'], 'Customer successfully deleted.');
}else{
  $messages['errors'] = $customer->errors;
}
$_SESSION['messages'] = $messages;

Header('Location: http://'.$_SERVER['HTTP_HOST'].'/businessManager/Views/customers.php');
?>