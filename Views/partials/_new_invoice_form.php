<div class="card">
  <div class="card_title">
    <h1>New invoice for sale number <?php echo $_GET['sale_id'] ?></h1>
  </div>
  <hr class="card_line">
  <div class="card_details">
    <div class="form_container">
      <form action="/businessManager/Controllers/new_invoice.php" method="POST">
        <div class="grid_container">

          <input hidden type="number" name='customer_id' value='<?php echo intval($_GET['customer_id']) ?>'>
          <input hidden type="number" name='sale_id' value='<?php echo intval($_GET['sale_id'])?>'>

          <div class="left_container">
            <label for="sent_date">Sent date</label>
          </div>
          <div class="right_container">
            <input type="date" id="sent_date" name="sent_date">
          </div>

          <div class="left_container">
            <label for="street_address">Due date</label>
          </div>
          <div class="right_container">
            <input type="date" id="due_date" name="due_date">
          </div>

          <div class="left_container">
            <label for="total">Total</label>
          </div>
          <div class="right_container">
            <input type="number" id="total" name="total" step="0.01" min="0">
          </div>

          <div class="right_container">
            <input type="submit">
          </div>
        </div>
      </form>
    </div>
  </div>
</div>