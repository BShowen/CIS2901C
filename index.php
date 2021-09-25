<?php
require __DIR__.'/Models/Page.php';

$page = new Page();

// This section of code is used when the user is being redirected to this page and there is a status message in the session. 
// This happens when the user uses the form on this page to log in. The form is submitted, a
// database connection and SQL statement are executed, if the credentials are incorrect then the user is redirected to this page with a 
// status message in the session. 
$user_message = isset($_SESSION['user_message']);

if($user_message){
  $message = $_SESSION['user_message'];
  $_SESSION['user_message'] = null;
}
?>

<main style='margin-left: 0;'>
  
  <div class='user_message'>
    <?php if($user_message){ ?>
      <h3 class='user_message_text'><?php echo $message ?></h3>
    <?php } ?>
  </div>

  <form action='/businessManager/Models/LoginHandler.php' method='POST'>
    <label for='user_name'>Username</label>
    <input type='text' id='user_name' name='user_name'>

    <label for='password'>Password</label>
    <input type='password' id='password' name='password'>

    <input type='submit' value='Login'>
  </form>

</main>

<?php
$page->render_footer();
?>