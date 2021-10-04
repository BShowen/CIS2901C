<?php 
require __DIR__."/../Models/Page.php";
require __DIR__."/../Models/Customer.php";
$page = new Page();
$db = new Database();
$customer = new Customer(intval($_GET['id']));

$sales = $customer->get_sales();
$sales_selection_list = "";
forEach($sales as $sale){
  extract($sale);
  $sales_selection_list .= "<option value='$sale_id'>$sale_total</option>";
}
?>

<main>
  <div class="customer_card">
    <h1><?php echo $customer->get_first_name()." ".$customer->get_last_name() ?></h1>
    <p><?php echo $customer->get_street_address();?></p>
    <p><?php echo $customer->get_city();?></p>
    <p><?php echo $customer->get_state(); ?></p>
    <p><?php echo $customer->get_zip(); ?></p>
  </div>

  <div class="customer_invoices">
    <div class="show_form_button">
      <button class="show_form collapsed">New invoice</button>
    </div>

    <div class="form_container">
      <form action="/businessManager/Controllers/new_invoice.php" method="POST">
        <div class="form_title">
          <h1>New invoice for <?php echo $customer->get_first_name(); ?></h1>
        </div>
        <div class="grid_container">
          <div class="grid_item_input">
            <input type="number" name="customer_id" hidden value='<?php echo $customer->get_customer_id(); ?>'>
          </div>

          <div class="grid_item_label">
            <label for="sale">Sale</label>
          </div>
          <div class="grid_item_input">
            <select name="sale_id" id="sale">
              <?php echo $sales_selection_list; ?>
            </select>
          </div>

          <div class="grid_item_label">
            <label for="sent_date">Send date</label>
          </div>
          <div class="grid_item_input">
            <input type="date" id="sent_date" name="sent_date">
          </div>

          <div class="grid_item_label">
            <label for="due_date">Due date</label>
          </div>
          <div class="grid_item_input">
            <input type="date" id="due_date" name="due_date">
          </div>

          <div class="grid_item_label">
            <label for="total">Total</label>
          </div>
          <div class="grid_item_input">
            <input type="number" id="total" name="total" min="0" step=".01">
          </div>

          <div class="grid_item_input">
            <input type="submit">
          </div>
        </div>
      </form>
    </div>
  </div>
</main>