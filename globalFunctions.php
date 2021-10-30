<?php 
// This function accepts an array of Message objects and puts those objects into session['messages'].
function set_session_messages($messages){
  $_SESSION['messages'] = [];
  foreach($messages as $messageObject){
    array_push($_SESSION['messages'], serialize($messageObject));
  }
}

// This function displays any messages stored in session['messages'];
function display_session_messages(){
  if(isset($_SESSION['messages'])){
    foreach($_SESSION['messages'] as $serialized_message){
      $message = unserialize($serialized_message);
      echo $message;
    }
    unset($_SESSION['messages']);
  }
}

// This function returns true if the table on the page has a new row, otherwise returns false. 
// You can also set the session variable that determines if the table has a new row. 
// This function is used in the controllers to tell the next page whether or not to animate the last row so the user knows that their submission was a success. 
function table_has_new_row($set_to_true = false){
  if($set_to_true){
    $_SESSION['table_has_new_row'] = true;
  }else{
    $has_row = false;
    if(isset($_SESSION['table_has_new_row'])){
      $has_row = true;
      unset($_SESSION['table_has_new_row']);
    }
    return $has_row;
  }
}

// This function will redirect the user to the desired page. 
// $page is a string. For example redirect_to("index");
function redirect_to($page){
  switch(strtolower($page)){
    case "login":
      Header("Location: http://".$_SERVER['HTTP_HOST']."/businessManager/index.php");
      break;
      case "signup":
        Header("Location: http://".$_SERVER['HTTP_HOST']."/businessManager/index.php?signup=1");
        break;
    case "dashboard":
      Header("Location: http://".$_SERVER['HTTP_HOST']."/businessManager/Views/dashboard.php");
      break;
    case "invoices":
      Header("Location: http://".$_SERVER['HTTP_HOST']."/businessManager/Views/invoices.php");
      break;
    case "customers":
      Header("Location: http://".$_SERVER['HTTP_HOST']."/businessManager/Views/customers.php");
      break;
    case "employees":
      Header("Location: http://".$_SERVER['HTTP_HOST']."/businessManager/Views/employees.php");
      break;
    case "inventory":
      Header("Location: http://".$_SERVER['HTTP_HOST']."/businessManager/Views/inventory.php");
      break;
    case "sales":
      Header("Location: http://".$_SERVER['HTTP_HOST']."/businessManager/Views/sales.php");
      break;
  }
}

// This function sets the appropriate cookies for a logged in user. 
// $employee is an employee object. 
function set_employee_cookie($employee){
  setcookie('employee_id', strval($employee->employee_id), 0, "/" );
  setcookie('authenticated', '1', 0, "/" );
  setcookie('business_id', strval($employee->business_id), 0, "/" );
}

// This function destroys all cookies and session associated with the currently logged in employee. 
function destroy_employee_cookie(){
  setcookie('business_id', '', 0, "/");
  setcookie('employee_id', '', 0, "/");
  setcookie('authenticated', '', 0, "/");
  session_reset();
}

//This function returns the currently logged in employee object. 
function current_logged_in_employee(){
  if(isset($_COOKIE['employee_id'])){
    return Employee::find_by_id($_COOKIE['employee_id']);
  }
  return null;
}

// This function returns all of the attributes from $_POST, but filters them through htmlspecialchars() first. 
function get_filtered_post_params(){
  $params = [];
  $attribute_names = array_keys($_POST);
  foreach($attribute_names as $attribute_name){
    $params[$attribute_name] = htmlspecialchars($_POST[$attribute_name]);
  } 
  return $params;
}
?>