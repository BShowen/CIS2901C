<?php 
require __DIR__."/../Models/Page.php";
require __DIR__."/../Models/Customer.php";
$page = new Page();
$db = new Database();
$customer = new Customer(intval($_GET['customer_id']));

// Retrieve the list of sales for this particular customer. \
// Create table rows for each sale.
$sales = $customer->get_sales();
$sales_table_rows = "";
forEach($sales as $sale){
  extract($sale);//sale_id, sales_person, sale_total, sale_date
  $sales_table_rows .= "
  <tr class='clickable' data-href='/businessManager/Views/customer.php?customer_id={$customer->get_customer_id()}&sale_id=$sale_id' data-id='$sale_id'>
    <td class='sale_number'>$sale_id</td>
    <td class='sales_person'>$sales_person</td>
    <td class='sale_total'>$sale_total</td>
    <td class='sale_date'>$sale_date</td>
  </tr>";
}

// Get the list of invoices for this particular customer. 
// If there are invoices then create a table row for each invoice. 
$invoices_requested = isset($_GET['sale_id']);
if($invoices_requested){
  $sale_id = intval($_GET['sale_id']);
  $invoices = $customer->get_invoices_for_sale(intval($sale_id));
  $invoice_table_rows = "";
  if(count($invoices) > 0){
    forEach($invoices as $invoice){
      extract($invoice); //invoice_id, sent_date, due_date, total, web_link
      $invoice_table_rows.="<tr>
        <td>$sale_id</td>
        <td>$invoice_id</td>
        <td>$sent_date</td>
        <td>$due_date</td>
        <td>$total</td>
        <td>$web_link</td>
      </tr>";
    }
  }else{
    $invoices_requested = false;
    $user_message = True;
    $message = 'There are no invoices for this sale.';
  }
}
?>

<main>

  <div class="user_message">
    <?php if(isset($user_message) && $user_message){ ?>
      <h3 class="user_message_text"><?php echo $message ?></h3>
    <?php } ?>
  </div>
  
  <div class="card">
    <div class="card_title">
      <h1><?php echo $customer->get_first_name()." ".$customer->get_last_name() ?></h1>
    </div>
    <hr class="card_line">
    <div class="card_details">
      <div class="grid_container">
        <div class="grid_item_label">
          Street address:
        </div>
        <div class="grid_item_input">
          <p><?php echo $customer->get_street_address();?></p>
        </div>

        <div class="grid_item_label">
          City:
        </div>
        <div class="grid_item_input">
          <p><?php echo $customer->get_city();?></p>
        </div>

        <div class="grid_item_label">
          State:
        </div>
        <div class="grid_item_input">
          <p><?php echo $customer->get_state(); ?></p>
        </div>

        <div class="grid_item_label">
          Zip:
        </div>
        <div class="grid_item_input">
          <p><?php echo $customer->get_zip(); ?></p>
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
        <h1>Invoices</h1>
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
</main>