<div class="card">
  <div class="card_title">
    <h1>Invoice details</h1>
  </div>

  <div class="card_details">
    <form action="/businessManager/Controllers/update_invoice.php" method="POST">
      <div class="grid_container">

        <div class="left_container">
        </div> 
        <div class="right_container">
          <?php $invoice_id = $invoice->invoice_id; ?>
          <input type="text" id="invoice_id" name="invoice_id" value="<?php echo $invoice_id; ?>" hidden >
        </div>
      
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
          <p><?php $total = $invoice->total; ?></p>
          <input type="number" id="total" name="total" step="0.01" min="0" placeholder="<?php echo $total; ?>" >
        </div>
        <hr>
        
        <div class="left_container">
          <p>Sent date</p>
        </div> 
        <div class="right_container">
          <p><?php $sent_date = $invoice->sent_date_form_value; ?></p>
          <input type="date" id="sent_date" name="sent_date" value="<?php echo $sent_date; ?>" >
        </div>
        <hr>

        <div class="left_container">
          <p>Due date</p>
        </div> 
        <div class="right_container">
          <p><?php $due_date = $invoice->due_date_form_value; ?></p>
          <input type="date" id="due_date" name="due_date" value="<?php echo $due_date; ?>" >
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