<?php
require __DIR__.'/Models/Page.php';
$page = new Page();

if(isset($_POST['user_name']) && isset($_POST['password'])){
  $_SESSION['user_name'] = $_POST['user_name'];
  $_SESSION['password'] = $_POST['password'];
  Header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
  exit;
}

$employee;
if(isset($_SESSION['user_name']) || isset($_SESSION['password'])){
  $user_name = strval($_SESSION['user_name']);
  $password = strval($_SESSION['password']);
  session_unset();
  $employee = Employee::find_by_user_name($user_name);
  if($employee->employee_exists && $employee->authenticate($password)){
    setcookie('employee_id', strval($employee->employee_id), 0, "/" );
    setcookie('authenticated', '1', 0, "/" );
    setcookie('business_id', strval($employee->business_id), 0, "/" );
    Header("Location: http://".$_SERVER['HTTP_HOST']."/businessManager/Views/dashboard.php");
  }
}

/*
 This section of code is used when the user is being redirected to this page and there is a status message in the session. This happens when the user uses the form on this page to log in. The form is submitted, a database connection and SQL statement are executed, if the credentials are incorrect then the user is redirected to this page with a status message in the session. 
*/

// $form_errors will be set to an empty array is $employee is not set. It will be set to an array if $employee is set. The array will contain error messages if the $employee object is not valid, otherwise it will be an empty array.
$form_errors = isset($employee) ? $employee->errors : [] ;
$error_messages = "";
if(count($form_errors) > 0){
  $errors = $employee->errors;
  foreach($errors as $error_message){
    $error_messages .= "<h3 class='user_message_text'>$error_message</h3>";
  }
}
?>

<main style='margin-left: 0;margin-top:7rem;'>
  
  <div class='user_message login_message'>
    <?php if($form_errors){ 
      echo $error_messages;
    } ?>
  </div>

  <div class="form_container">
    <form action='/businessManager/index.php' method='POST'>
      <div class="form_title">
        <h1>Login</h1>
      </div>
      <div class="grid_container">
        <div class="grid_item_label">
          <label for='user_name'>Username</label>
        </div>
        <div class="grid_item_input">
          <input type='text' id='user_name' name='user_name'>
        </div>
        
        <div class="grid_item_label">
          <label for='password'>Password</label>
        </div>
         <div class="grid_item_input">
          <input type='password' id='password' name='password'>
        </div>
        
        <div class="grid_item_input">
          <button type="submit">Submit</button>
        </div>
      </div>
    </form>
  </div>

</main>

<?php
$page->render_footer();
?>