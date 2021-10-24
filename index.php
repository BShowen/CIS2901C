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
$messages = ['errors'=>[], 'success'=>[]];
if(isset($employee)){
  foreach($employee->errors as $error_message){
    array_push($messages['errors'], $error_message  );
  }
}
$_SESSION['messages'] = $messages;

// type is a string. it should be set to either "errors" or "success"
function print_message($type){ 
  foreach($_SESSION['messages'][$type] as $message){
    echo "<h3 class='user_message_text'> $message </h3>";
  }
  $_SESSION['messages'][$type] = [];
}

$signing_up = False;
if(isset($_GET['signup'])){
  $signing_up = boolval($_GET['signup']);
}
?>

<main style='margin-left: 0;'>
    
  <!-- Login form -->
  <div class="authorization_form_container">
    <div class="card">
      <?php if($signing_up){ ?> 
        <!-- Render the signup form -->
          <div class="card_title">
            <h1>Sign up</h1>
          </div>
          <hr class="card_line">
          <div class="card_details"> 
            <form action='/businessManager/Controllers/signup.php' method='POST' >
              <div class="grid_container">

                <div class="grid_item_label">
                  <label for='user_name'>Business name</label>
                </div>
                <div class="grid_item_input">
                  <input type='text' id='business_name' name='business_name'>
                </div>

                <div class="grid_item_label">
                  <label for='user_name'>First name</label>
                </div>
                <div class="grid_item_input">
                  <input type='text' id='first_name' name='first_name'>
                </div>

                <div class="grid_item_label">
                  <label for='user_name'>Last name</label>
                </div>
                <div class="grid_item_input">
                  <input type='text' id='last_name' name='last_name'>
                </div>

                <div class="grid_item_label">
                  <label for='user_name'>Username</label>
                </div>
                <div class="grid_item_input">
                  <input type='text' id='user_name' name='user_name'>
                </div>

                <div class="grid_item_label">
                  <label for='user_name'>Email address</label>
                </div>
                <div class="grid_item_input">
                  <input type='text' id='email_address' name='email_address'>
                </div>
                
                <div class="grid_item_label">
                  <label for='password'>Password</label>
                </div>
                <div class="grid_item_input">
                  <input type='password' id='password' name='password'>
                </div>
                
                <div class="grid_item_label">
                  <label for='verify_password'>Verify Password</label>
                </div>
                <div class="grid_item_input">
                  <input type='password' id='verify_password' name='verify_password'>
                </div>
                
                <div class="grid_item_input">
                  <button type="submit">Submit</button>
                </div>
              </div>
            </form>
            <div id="sign_in_link_container">
              <p>Already a member? <a href="/businessManager/index.php?signup=0">Login</a></p>
            </div>
          </div>
      <?php }else{ ?>
          <!-- Render the login form -->
          <div class="card_title">
            <h1>Login</h1>
          </div>
          <hr class="card_line">
          <div class="card_details"> 
            <form action='/businessManager/index.php' method='POST' class="authentication_form">
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
            <div id="signup_link_container">
              <p>Not a member? <a href="/businessManager/index.php?signup=1">Signup</a></p>
            </div>
          </div>
        <?php } ?>
    </div>
  </div>

  <div class='user_message login_message'>
    <?php 
      print_message('errors');
    ?>
  </div>

</main>

<?php
$page->render_footer();
?>