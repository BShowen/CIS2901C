<?php
require __DIR__.'/globalFunctions.php';
require __DIR__.'/Models/Page.php';
require __DIR__.'/Models/Message.php';
$page = new Page();

// This variable is used to determine which form to render on this page. 
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
          <hr>
          <div class="card_details"> 
            <form action='/businessManager/Controllers/signup.php' method='POST' >
              <div class="grid_container">

                <div class="left_container">
                  <label for='user_name'>Business name</label>
                </div>
                <div class="right_container">
                  <input type='text' id='business_name' name='business_name'>
                </div>

                <div class="left_container">
                  <label for='user_name'>First name</label>
                </div>
                <div class="right_container">
                  <input type='text' id='first_name' name='first_name'>
                </div>

                <div class="left_container">
                  <label for='user_name'>Last name</label>
                </div>
                <div class="right_container">
                  <input type='text' id='last_name' name='last_name'>
                </div>

                <div class="left_container">
                  <label for='user_name'>Username</label>
                </div>
                <div class="right_container">
                  <input type='text' id='user_name' name='user_name'>
                </div>

                <div class="left_container">
                  <label for='user_name'>Email address</label>
                </div>
                <div class="right_container">
                  <input type='text' id='email_address' name='email_address'>
                </div>
                
                <div class="left_container">
                  <label for='password'>Password</label>
                </div>
                <div class="right_container">
                  <input type='password' id='password' name='password'>
                </div>
                
                <div class="left_container">
                  <label for='verify_password'>Verify Password</label>
                </div>
                <div class="right_container">
                  <input type='password' id='verify_password' name='verify_password'>
                </div>
                
                <div class="right_container">
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
          <hr>
          <div class="card_details"> 
            <form action='/businessManager/Controllers/login.php' method='POST' class="authentication_form">
              <div class="grid_container">

                <div class="left_container">
                  <label for='user_name'>Username</label>
                </div>
                <div class="right_container">
                  <input type='text' id='user_name' name='user_name'>
                </div>
                
                <div class="left_container">
                  <label for='password'>Password</label>
                </div>
                <div class="right_container">
                  <input type='password' id='password' name='password'>
                </div>
                
                <div class="right_container">
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

  <div class="notification_container center_fix">
    <?php require __DIR__.'/Views/partials/_user_message.php'; ?>
  </div>
  
</main>

<?php
$page->render_footer();
?>