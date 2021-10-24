<?php 
require __DIR__.'/../Models/Database.php';
require __DIR__.'/../Models/Employee.php';
$db = new Database();

$params = $_POST;
$attribute_names = array_keys($params);
foreach($attribute_names as $attribute_name){
  $params[$attribute_name] = htmlspecialchars($params[$attribute_name]);
}
$params['business_id'] = intval($_COOKIE['business_id']);
$params['password'] = strval($_POST['temp_password']);

// var_dump($params);exit;


$employee = new Employee($params);

$messages = ['errors'=>[], 'success'=>[]];
if($employee->save()){
  array_push($messages['success'], 'Employee successfully added');
}else{
  $messages['errors'] = $employee->errors;
}
$_SESSION['messages'] = $messages;

Header('Location: http://'.$_SERVER['HTTP_HOST'].'/businessManager/Views/employees.php');

// send a response back to the caller. Success for Failure. 
?>