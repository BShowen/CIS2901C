<div class="card">
  <div class="card_title">
    <h1>Customer details</h1>
  </div>

  <div class="card_details">
    <div class="grid_container">
      <div class="left_container">
        <p>First name</p>
      </div> 
      <div class="right_container">
        <p><?php echo $customer->first_name; ?></p>
      </div>
      <hr>

      <div class="left_container">
        <p>Last name</p>
      </div> 
      <div class="right_container">
        <p><?php echo $customer->last_name; ?></p>
      </div>
      <hr>
      
      <!-- <div class="left_container">
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
      <hr> -->

      <div class="left_container">
        <p>Street address</p>
      </div>
      <div class="right_container">
        <div>
          <p>
            <?php 
            echo $customer->street_address."<br>";
            echo $customer->city.", ";
            echo $customer->state." " ;
            echo $customer->zip; 
            ?>
          </p>
        </div>
      </div>
      <hr>

      <div class="left_container">
        <button id="edit_details" data-url="<?php echo $_SERVER['REQUEST_URI']."&edit=1" ?>">Edit</button>
      </div>

    </div>
  </div>
</div>
