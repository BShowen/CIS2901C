<?php 
require __DIR__."/../Models/Message.php";
require __DIR__."/../globalFunctions.php";
require __DIR__."/../Models/Page.php";
$page = new Page();

define('CUSTOMER_ID', intval($_GET['customer_id']));
$customer = Customer::find_by_id(CUSTOMER_ID);
$sales = $customer->sales;
$create_invoice = isset($_GET['new_invoice']) ? intval($_GET['new_invoice']) : 0 ;
$invoices_requested = isset($_GET['sale_id']) && !$create_invoice;
$sales_table_rows = "";
$invoice_table_rows = "";
 
// Create table rows for each sale.
forEach($sales as $sale){
  $sales_table_rows .= "
  <tr 
    class='clickable' 
    data-href='/businessManager/Views/customer.php?customer_id={$customer->customer_id}&sale_id=$sale->sale_id' data-id='$sale->sale_id'>
    <td class='sale_number'>$sale->sale_id</td>
    <td class='sales_person'>{$sale->sales_person->first_name}</td>
    <td class='sale_total'>$sale->sale_total</td>
    <td class='sale_date'>$sale->sale_date</td>
    <td class='action_buttons'>
      <a class='action_button' href='/businessManager/Controllers/delete_sale.php?sale_id=$sale->sale_id'>Delete</a> | 
      <!-- <a class='action_button' data-id='$customer->customer_id' href='#'>Edit</a> -->
      <a class='action_button' data-id='$customer->customer_id' href='/businessManager/Views/customer.php?customer_id={$customer->customer_id}&sale_id={$sale->sale_id}&new_invoice=1'>Create invoice</a>
    </td>
  </tr>";
}

// If there are invoices then create a table row for each invoice. 
if($invoices_requested){
  $sale_id = intval($_GET['sale_id']);
  $sale = Sale::find_by_id($sale_id);
  $invoices = $sale->invoices;
  if(!empty($invoices)){
    $current_row = 0;
    $last_row = count($invoices);
    forEach($invoices as $invoice){
      $current_row++;
      if($current_row == $last_row && table_has_new_row()){
        $invoice_table_rows.="<tr class='new_row'>";
      }else{
        $invoice_table_rows.="<tr>";
      }
      $invoice_table_rows.="<td>$sale->sale_id</td>
                            <td>$invoice->invoice_id</td>
                            <td>$invoice->sent_date</td>
                            <td>$invoice->due_date</td>
                            <td>$invoice->total</td>
                            <td>$invoice->web_link</td>
                            <td class='action_buttons'>
                            <a class='action_button' href='/businessManager/Controllers/delete_invoice.php?invoice_id=$invoice->invoice_id'>Delete</a> 
                            <!-- | <a class='action_button' data-id='$invoice->invoice_id' href='#'>Edit</a> -->
                            </td>
                          </tr>";
    }
  }else{
    $invoices_requested = False;
    $messages = [];
    foreach($sale->errors as $error_message){
      array_push($messages, new Message("error", $error_message));
    }
    set_session_messages($messages);
  }
}
?>

<main>

  <div class="user_message">
    <?php display_session_messages(); ?>
  </div>
  
  <div class="card">
    <div class="card_title">
      <h1><?php echo $customer->first_name." ".$customer->last_name ?></h1>
    </div>
    <hr class="card_line">
    <div class="card_details">
      <div class="grid_container">
        <div class="grid_item_label">
          Street address:
        </div>
        <div class="grid_item_input">
          <p><?php echo $customer->street_address;?></p>
        </div>

        <div class="grid_item_label">
          City:
        </div>
        <div class="grid_item_input">
          <p><?php echo $customer->city;?></p>
        </div>

        <div class="grid_item_label">
          State:
        </div>
        <div class="grid_item_input">
          <p><?php echo $customer->state; ?></p>
        </div>

        <div class="grid_item_label">
          Zip:
        </div>
        <div class="grid_item_input">
          <p><?php echo $customer->zip; ?></p>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card_title">
      <h1>Sales</h1>
    </div>
    <hr class="card_line">
    <div class="card_details">
      <div class="table_container">
        <table>
          <thead>
            <tr class="no-hover">
              <th scope="col">Sale number</th>
              <th scope="col">Sales person</th>
              <th scope="col">Sale total</th>
              <th scope="col">Sale date</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php echo $sales_table_rows; ?>
          </tbody>
        </table>  
      </div>
    </div>
  </div>

  <?php if($invoices_requested){ ?>
    <div class="card">
      <div class="card_title">
        <h1>Invoices for sale <?php echo $sale_id ?></h1>
      </div>
      <hr class="card_line">
      <div class="card_details">
        <div class="table_container">
          <table>
            <thead>
              <tr class="no-hover">
                <th scope="col">Sale number</th>
                <th scope="col">Invoice number</th>
                <th scope="col">Sent date</th>
                <th scope="col">Due date</th>
                <th scope="col">Total</th>
                <th scope="col">Web link</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php echo $invoice_table_rows; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php } ?>

  <?php if($create_invoice){ ?>
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

            <div class="grid_item_label">
              <label for="sent_date">Sent date</label>
            </div>
            <div class="grid_item_input">
              <input type="date" id="sent_date" name="sent_date">
            </div>

            <div class="grid_item_label">
              <label for="street_address">Due date</label>
            </div>
            <div class="grid_item_input">
              <input type="date" id="due_date" name="due_date">
            </div>

            <div class="grid_item_label">
              <label for="total">Total</label>
            </div>
            <div class="grid_item_input">
              <input type="number" id="total" name="total" step="0.01" min="0">
            </div>

            <div class="grid_item_input">
              <input type="submit">
            </div>
          </div>
        </form>
      </div>
    </div>
  <?php }?>
</main>