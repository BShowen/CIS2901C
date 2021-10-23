<?php 

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
      // log the user in and redirect to dashboard.
      setcookie('employee_id', strval($employee->employee_id), 0, "/" );
      setcookie('authenticated', '1', 0, "/" );
      setcookie('business_id', strval($employee->business_id), 0, "/" );
      Header("Location: http://".$_SERVER['HTTP_HOST']."/businessManager/Views/dashboard.php");
      exit;
    }
  }
  /*
  If this is reached then that means the employee object was not valid to save, but the business object was valid and
  has been saved to the database. We now need to delete the business object from that database because it doesn't have any employees.
  If the user decides to close the browser then we have an orphaned business object in the database. We need to delete the business
  object.
  */
  $query = "DELETE FROM Businesses WHERE business_id = ?";
  $params = ['business_id'=>$business->business_id];
  $db = new Database();
  $db->execute_sql_statement($query, $params);
}

/*
If this is reached then either the business or the employee object was not valid to be saved. 
We need to set the appropriate error messages and redirect the user back to the form. 
*/
set_error_messages($business, $employee);
Header("Location: http://".$_SERVER['HTTP_HOST']."/businessManager/index.php?signup=1");
exit;


/*
This helper function takes in the two objects from this script ($employee, and $business) and retrieves the error messages 
from each object, if any. 
*/
function set_error_messages($business, $employee){
  $errors = array_merge(
    $business->is_valid ? [] : $business->errors, 
    $employee->is_valid ? [] : $employee->errors
  );

  $messages = ['errors'=>[], 'success'=>[]];
  foreach($errors as $message){
    array_push($messages['errors'], $message);
  }
  $_SESSION['messages'] = $messages;
}
?>