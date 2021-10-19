<?php
require __DIR__.'/../Models/Database.php';
require __DIR__.'/../Models/Sale.php';
$db = new Database();

$params = $_POST;
$params['customer_id'] = intval($params['customer_id']);
$params['sale_total'] = doubleval($params['sale_total']);
$params['employee_id'] = intval($_COOKIE['employee_id']);
$sale = new Sale($params);
$messages = ['errors'=>[], 'success'=>[]];
if($sale->save()){
  array_push($messages['success'], 'Sale successfully added');
}else{
  $messages['errors'] = $sale->errors;
}

$_SESSION['messages'] = $messages;

Header('Location: http://'.$_SERVER['HTTP_HOST'].'/businessManager/Views/sales.php');
?>