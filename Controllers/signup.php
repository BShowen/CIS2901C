<?php 

require __DIR__.'/../Models/Business.php';
require __DIR__.'/../Models/Employee.php';


// retrieve the data from post. 

/*
Verify that all fields have been filled out. 
The appropriate model should catch this
*/

/* 
Verify that the business name isn't taken.
This can be done in the business model.
*/ 
$business_name = htmlspecialchars($_POST['business_name']);
$business = new Business(['business_name'=> $business_name]);
unset($_POST['business_name']);

/*
Verify that the user name isn't taken.
Verify that the email address name isn't taken.
This can be done in the user model
*/

$employee_params;
$keys = array_keys($_POST);
foreach($keys as $key){
  $employee_params[$key] = htmlspecialchars($_POST[$key]);
}
$employee_params['is_admin'] = 1;
$employee = new Employee($employee_params);
if($employee->is_valid && $business->is_valid){
  $business->save();
  $employee->business_id = $business->business_id;
  $employee->save();
  // log the user in and redirect to dashboard.
  setcookie('employee_id', strval($employee->employee_id), 0, "/" );
  setcookie('authenticated', '1', 0, "/" );
  setcookie('business_id', strval($employee->business_id), 0, "/" );
  Header("Location: http://".$_SERVER['HTTP_HOST']."/businessManager/Views/dashboard.php");
  exit;
}else{
  $business_error_messages = $business->is_valid ? [] : $business->errors;
  $employee_error_messages = $employee->is_valid ? [] : $employee->errors;
  $messages = ['errors'=>[], 'success'=>[]];
  foreach($business_error_messages as $message){
    array_push($messages['errors'], $message);
  }
  foreach($employee_error_messages as $message){
    array_push($messages['errors'], $message);
  }
  $_SESSION['messages'] = $messages;
  Header("Location: http://".$_SERVER['HTTP_HOST']."/businessManager/index.php?signup=1");
  exit;
}

/*
if all fields are provided, and valid, then create the business and employee (as an admin) and redirect to dashboard
Otherwise redirect to the index page ?signup=1 with appropriate errors
*/

?>