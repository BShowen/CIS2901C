<div class="card">
  <div class="card_title">
    <h1>Sale details</h1>
  </div>

  <div class="card_details">
    <div class="grid_container">
      <div class="left_container">
        <p>Sale number</p>
      </div> 
      <div class="right_container">
        <p><?php echo $sale->sale_id; ?></p>
      </div>
      <hr>

      <div class="left_container">
        <p>Sales person</p>
      </div> 
      <div class="right_container">
        <p><?php echo $sale->sales_person->first_name." ".$sale->sales_person->last_name; ?></p>
      </div>
      <hr>

      <div class="left_container">
        <p>Customer</p>
      </div> 
      <div class="right_container">
        <p><?php echo $sale->customer->first_name." ".$sale->customer->last_name; ?></p>
      </div>
      <hr>

      <div class="left_container">
        <p>Sale total</p>
      </div>
      <div class="right_container">
        <p><?php echo $sale->sale_total_formatted; ?></p>
      </div>  
      <hr>

      <div class="left_container">
        <p>Sale date</p>
      </div> 
      <div class="right_container">
        <p><?php echo $sale->sale_date_formatted; ?></p>
      </div>
      <hr>

      <div class="left_container">
        <button id="edit_details" data-url="<?php echo $_SERVER['REQUEST_URI']."&edit=1" ?>">Edit</button>
      </div>

    </div>
  </div>
</div>
