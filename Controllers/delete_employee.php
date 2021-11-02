<?php
require __DIR__.'/../globalFunctions.php';
require __DIR__.'/../Models/Message.php';
require __DIR__.'/../Models/Employee.php';

$params = get_filtered_get_params();

$currently_logged_in_employee = current_logged_in_employee();
$employee = Employee::find_by_id($params['employee_id']);

if($currently_logged_in_employee->employee_id == $employee->employee_id){
  set_session_messages([new Message("error", "You cannot delete yourself.")]);
  redirect_to('employees');
  exit;
}

$messages = [];
if($employee->delete()){
  array_push( $messages, new Message("success", "Employee successfully deleted.") );
}else{
  $errors = $employee->errors;
  foreach($errors as $error_message){
    array_push($messages, new Message("error", $error_message));
  }
}

set_session_messages($messages);

redirect_to('employees');
?>