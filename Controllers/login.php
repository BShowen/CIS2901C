<?php 
require __DIR__."/../globalFunctions.php";
require __DIR__."/../Models/Employee.php";
require __DIR__."/../Models/Message.php";

if(isset($_POST['user_name']) || isset($_POST['password'])){
  $user_name = strval($_POST['user_name']);
  $password = strval($_POST['password']);
  try{
    $employee = Employee::find_by_user_name($user_name);
    if($employee->authenticate($password)){
      set_employee_cookie($employee);
      redirect_to("dashboard");
    }
  }catch(Error $e){
    set_session_messages([new Message("error", $e->getMessage())]);
  }
}
redirect_to("login");
exit;

?>