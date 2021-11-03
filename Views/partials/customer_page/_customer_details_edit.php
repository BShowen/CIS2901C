<div class="card">
  <div class="card_title">
    <h1>Customer details</h1>
  </div>

  <div class="card_details">
    <form action="/businessManager/Controllers/update_customer.php" method="POST">
      <div class="grid_container">


        <div class="left_container">
        </div> 
        <div class="right_container">
          <?php $customer_id= $customer->customer_id; ?>
          <input type="text" id="customer_id" name="customer_id" value="<?php echo $customer_id; ?>" hidden >
        </div>
      
        <div class="left_container">
          <p>First name</p>
        </div> 
        <div class="right_container">
          <?php $first_name = $customer->first_name; ?>
          <input type="text" id="first_name" name="first_name" placeholder="<?php echo $first_name; ?>" >
        </div>
        <hr>

        <div class="left_container">
          <p>Last name</p>
        </div> 
        <div class="right_container">
          <?php $last_name = $customer->last_name; ?>
          <input type="text" id="last_name" name="last_name" placeholder="<?php echo $last_name; ?>" >
        </div>
        <hr>
        
        <div class="left_container">
          <p>Phone</p>
        </div> 
        <div class="right_container">
          <p><?php ?></p>
        </div>
        <hr>
        
        <div class="left_container">
          <p>Email</p>
        </div> 
        <div class="right_container">
          <p><?php ?></p>
        </div>
        <hr>

        <div class="left_container">
          Street address
        </div>
        <div class="right_container">
          <?php $street_address = $customer->street_address; ?>
          <input type="text" id="street_address" name="street_address" placeholder="<?php echo $street_address; ?>">
        </div>
        <hr>

        <div class="left_container">
          City
        </div>
        <div class="right_container">
          <?php $city = $customer->city; ?>
          <input type="text" id="city" name="city" placeholder="<?php echo $city; ?>">
        </div>
        <hr>

        <div class="left_container">
          State
        </div>
        <div class="right_container">
          <?php $state = $customer->state; ?>
          <input type="text" id="state" name="state" placeholder="<?php echo $state; ?>">
        </div>
        <hr>

        <div class="left_container">
          Zip
        </div>
        <div class="right_container">
          <?php $zip = $customer->zip; ?>
          <input type="number" id="zip" name="zip" placeholder="<?php echo $zip; ?>">
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