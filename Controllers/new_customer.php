<?php 
require __DIR__.'/../Models/Database.php';
require __DIR__.'/../Models/Customer.php';
$db = new Database();

$params = $_POST;
$attribute_names = array_keys($params);
foreach($attribute_names as $attribute_name){
  $params[$attribute_name] = htmlspecialchars($params[$attribute_name]);
}
$params['zip'] = intval($params['zip']);

$customer = new Customer($params);
$messages = ['errors'=>[], 'success'=>[]];
if($customer->save()){
  array_push($messages['success'], 'Customer successfully added');
}else{
  $messages['errors'] = $customer->errors;
}
$_SESSION['messages'] = $messages;

Header('Location: http://'.$_SERVER['HTTP_HOST'].'/businessManager/Views/customers.php');

// send a response back to the caller. Success for Failure. 
?>