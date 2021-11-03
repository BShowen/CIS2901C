<div class="card">
  <div class="card_title">
    <h1>Employee details</h1>
  </div>

  <div class="card_details">
    <form action="/businessManager/Controllers/update_employee.php" method="POST">
      <div class="grid_container">


        <div class="left_container">
        </div> 
        <div class="right_container">
          <?php $employee_id= $employee->employee_id; ?>
          <input type="text" id="employee_id" name="employee_id" value="<?php echo $employee_id; ?>" hidden >
        </div>
      
        <div class="left_container">
          <p>First name</p>
        </div> 
        <div class="right_container">
          <?php $first_name = $employee->first_name; ?>
          <input type="text" id="first_name" name="first_name" placeholder="<?php echo $first_name; ?>" >
        </div>
        <hr>

        <div class="left_container">
          <p>Last name</p>
        </div> 
        <div class="right_container">
          <?php $last_name = $employee->last_name; ?>
          <input type="text" id="last_name" name="last_name" placeholder="<?php echo $last_name; ?>" >
        </div>
        <hr>
        
        <div class="left_container">
          <p>User name</p>
        </div> 
        <div class="right_container">
        <?php $user_name = $employee->user_name; ?>
          <input type="text" id="user_name" name="user_name" placeholder="<?php echo $user_name; ?>" >
        </div>
        <hr>
        
        <div class="left_container">
          <p>Email</p>
        </div> 
        <div class="right_container">
          <p><?php $email_address = $employee->email_address; ?></p>
          <input type="text" id="email_address" name="email_address" placeholder="<?php echo $email_address; ?>" >
        </div>
        <hr>

        <div class="left_container">
          <p>Is admin?</p>
        </div>
        <div class="right_container">
          <?php $is_admin = $employee->is_admin; ?>
          <select id="is_admin" name="is_admin">
            <option value="0">No</option>
            <option value="1">Yes</option>
          </select>
        </div>
        <hr>

        <div class="left_container">
          <button type="button" id="cancel">Cancel</button>
        </div>
        <div class="right_container">
          <button type="submit">Save</button>
        </div>
      </div>
    </form>
  </div>
</div>