<?php
require __DIR__.'/Models/Page.php';
require __DIR__.'/Models/Authenticator.php';

$page = new Page();

if(isset($_POST['user_name']) && isset($_POST['password'])){
  $_SESSION['user_name'] = $_POST['user_name'];
  $_SESSION['password'] = $_POST['password'];
  Header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
  exit;
}

$authenticator;
if(isset($_SESSION['user_name']) || isset($_SESSION['password'])){
  $user_name = strval($_SESSION['user_name']);
  $password = strval($_SESSION['password']);
  session_unset();
  $authenticator = new Authenticator($user_name, $password);
}
// This section of code is used when the user is being redirected to this page and there is a status message in the session. 
// This happens when the user uses the form on this page to log in. The form is submitted, a
// database connection and SQL statement are executed, if the credentials are incorrect then the user is redirected to this page with a 
// status message in the session. 
$form_errors = isset($authenticator) ? count($authenticator->get_errors()) > 0 : NULL ;
$error_messages = "";
if($form_errors){
  $form_errors = $authenticator->get_errors();
  foreach($form_errors as $error_message){
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