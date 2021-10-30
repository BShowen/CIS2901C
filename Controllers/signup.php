<?php 

require __DIR__.'/../globalFunctions.php';
require __DIR__.'/../Models/Message.php';
require __DIR__.'/../Models/Database.php';
require __DIR__.'/../Models/Business.php';
require __DIR__.'/../Models/Employee.php';

$business_name = htmlspecialchars($_POST['business_name']);
$business = new Business(['business_name'=> $business_name]);
unset($_POST['business_name']);

$employee_params;
$keys = array_keys($_POST);
foreach($keys as $key){
  $employee_params[$key] = htmlspecialchars($_POST[$key]);
}
$employee_params['is_admin'] = 1;
$employee = new Employee($employee_params);
// is_valid needs to be called before the business_id is set on the employee object, otherwise is_valid will return true
// even if the password doesn't match the password verify. This is a flaw in the logic with how the has_valid_attributes method 
// works in the Employee model. 
if($business->is_valid){
  $business->save();
  if($employee->is_valid){
    $employee->business_id = $business->business_id;
    if($employee->save()){
      set_employee_cookie($employee);
      redirect_to("dashboard");
      exit;
    }
  }
  /*
  If this is reached then that means the employee object was not valid to save, but the business object was valid and
  has been saved to the database. We now need to delete the business object from that database because it doesn't have any employees.
  If the user decides to close the browser then we have an orphaned business object in the database. We need to delete the business
  object.
  */
  $business->delete();
}

/*
If this is reached then either the business or the employee object was not valid to be saved. 
We need to set the appropriate error messages and redirect the user back to the form. 
*/
$messages = [];
$errors = array_merge(
  $business->is_valid ? [] : $business->errors, 
  $employee->is_valid ? [] : $employee->errors
);
foreach($errors as $message){
  array_push($messages, new Message("error", $message));
}
set_session_messages($messages);
redirect_to("signup");
exit;
?>