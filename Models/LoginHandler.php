<?php 
session_start();
require __DIR__."/Database.php";
$db = new Database();

require __DIR__."/CurrentUser.php";


// get the username and password from POST. 
$user_name = $_POST['user_name'];
$password = $_POST['password'];

// Get the password digest associated with the user name.
$query = "SELECT employee_id, password_digest FROM Employees WHERE user_name = ?";
$params = ['user_name'=>$_POST['user_name']];
$results = $db->execute_sql_statement($query, $params);
if($results[0]){
  $results = $results[1];
  $row = $results->fetch_assoc();
  extract($row);
}
// Validate the username and password against the database. 
if(password_verify($password, $password_digest)){
  // Set the cookie
  setcookie('current_user', $employee_id, 0, "/");
}else{
  // Don't set the cookie. 
  // Create an error message
  $_SESSION['user_message'] = "Incorrect user name or password";
}
Header("Location: http://".$_SERVER['HTTP_HOST']."/businessManager/index.php");

?>