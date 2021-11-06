<?php 
require __DIR__.'/../globalFunctions.php';
require __DIR__.'/../Models/Message.php';
require __DIR__.'/../Models/Employee.php';


$params = get_filtered_post_params();

/*
I set the business_id on the server side. If I set the business_id using a hidden form input, then this can lead to problems if a users changes the value of this field before submitting. Its safer to set the business_id on the server side. 
*/
$params['business_id'] = intval($_COOKIE['business_id']);

// This is a hack. Users should be given temp passwords. Then when they log in they should be instructed to change their password. I have no implemented this yet. So this is my fix for now. 
// $params['password'] = strval($_POST['temp_password']);

$employee = new Employee($params);
$employee->password = strtolower($employee->first_name);
$employee->verify_password = strtolower($employee->first_name);
$employee->temp_password = true;

$messages = [];
if($employee->save()){
  array_push($messages, new Message("success", "Employee successfully added"));
  table_has_new_row("true");
}else{
  $errors = $employee->errors;
  foreach($errors as $error_message){
    array_push($messages, new Message("error", $error_message));
  }
}
set_session_messages($messages);
redirect_to("employees");
?>