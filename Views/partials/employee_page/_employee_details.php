<div class="card">
  <div class="card_title">
    <h1>Employee details</h1>
  </div>

  <div class="card_details">
    <div class="grid_container">
      <div class="left_container">
        <p>First name</p>
      </div> 
      <div class="right_container">
        <p><?php echo $employee->first_name; ?></p>
      </div>
      <hr>

      <div class="left_container">
        <p>Last name</p>
      </div> 
      <div class="right_container">
        <p><?php echo $employee->last_name; ?></p>
      </div>
      <hr>
      
      <div class="left_container">
        <p>User name</p>
      </div> 
      <div class="right_container">
        <p><?php echo $employee->user_name; ?></p>
      </div>
      <hr>
      
      <div class="left_container">
        <p>Email</p>
      </div> 
      <div class="right_container">
        <p><?php echo $employee->email_address; ?></p>
      </div>
      <hr>

      <div class="left_container">
        <p>Is admin?</p>
      </div>
      <div class="right_container">
        <p>
          <?php 
            echo $employee->is_admin ? "Yes" : "No";
          ?>
        </p>
      </div>
      <hr>

      <div class="left_container">
        <button id="edit_details" data-url="<?php echo $_SERVER['REQUEST_URI']."&edit=1" ?>">Edit</button>
      </div>

    </div>
  </div>
</div>
