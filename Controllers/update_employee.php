<?php 
require __DIR__.'/../Models/Employee.php';
require __DIR__.'/../Models/Message.php';
require __DIR__.'/../globalFunctions.php';

$params = get_filtered_post_params();

foreach(array_keys($params) as $key){
  if(strlen($params[$key]) == 0){
    unset($params[$key]);
  }
}

/* 
  I call new Employee rather than Employee::find_by_id() because find_by_id will set every attribute in the Employee object. I don't want that. I want to set only the attributes that are being updated. Therefor I call new Employee() and pass in only the attributes that are passed to me by the form submission. 
*/
if(count($params) > 1){
  $employee = new Employee($params);
  if($employee->update()){
    set_session_messages([new Message("success", "Employee details have been updated.")]);
  }else{
    $messages = [];
    foreach($employee->errors as $error_message){
      array_push($messages, new Message("error", $error_message));
    }
    set_session_messages($messages);
  }
}else{
  set_session_messages([new Message("error", "Nothing was updated.")]);
}



redirect_to("employee", "?employee_id={$params['employee_id']}");
?>