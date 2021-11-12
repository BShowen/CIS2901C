<div class="card">
  <div class="card_title">
    <h1>Sale details</h1>
  </div>

  <div class="card_details">
    <form action="/businessManager/Controllers/update_sale.php" method="POST">
      <div class="grid_container">
        <div class="left_container">
          <p>Sale number</p>
        </div> 
        <div class="right_container">
          <p><?php echo $sale->sale_id; ?></p>
        </div>
        <hr>

        <div class="left_container">
        </div> 
        <div class="right_container">
          <?php $sale_id = $sale->sale_id; ?>
          <input type="text" id="sale_id" name="sale_id" value="<?php echo $sale_id; ?>" hidden >
        </div>
      
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
          <?php $sale_total = $sale->sale_total; ?>
          <input type="number" id="sale_total" name="sale_total" step="0.01" min="0" 
          placeholder="<?php echo $sale_total; ?>">
        </div>
        <hr>

        <div class="left_container">
          <p>Sale date</p>
        </div>
        <div class="right_container">
          <?php $sale_date = $sale->sale_date_form_value; ?>
          <input type="date" id="sale_date" name="sale_date" value="<?php echo $sale_date; ?>">
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