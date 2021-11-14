<div class="card">
  <div class="card_title">
    <h1>Invoice details</h1>
  </div>

  <div class="card_details">
    <div class="grid_container">
      <div class="left_container">
        <p>Invoice number</p>
      </div> 
      <div class="right_container">
        <p><?php echo $invoice->invoice_id; ?></p>
      </div>
      <hr>

      <div class="left_container">
        <p>Customer</p>
      </div> 
      <div class="right_container">
        <p><?php echo $invoice->customer->first_name; ?></p>
      </div>
      <hr>
      
      <div class="left_container">
        <p>Invoice total</p>
      </div> 
      <div class="right_container">
        <p><?php echo $invoice->total_formatted; ?></p>
      </div>
      <hr>
      
      <div class="left_container">
        <p>Sent date</p>
      </div> 
      <div class="right_container">
        <p><?php echo $invoice->sent_date_formatted; ?></p>
      </div>
      <hr>

      <div class="left_container">
        <p>Due date</p>
      </div>
      <div class="right_container">
        <p><?php echo $invoice->due_date_formatted; ?></p>
      </div>
      <hr>

      <div class="left_container">
        <button id="edit_details" data-url="<?php echo $_SERVER['REQUEST_URI']."&edit=1" ?>">Edit</button>
      </div>

    </div>
  </div>
</div>
