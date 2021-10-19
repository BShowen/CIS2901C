<?php 
require __DIR__."/../Models/Page.php";

$page = new Page();
$db = new Database();

$has_error_message = isset($_SESSION['messages']['errors']) ? count($_SESSION['messages']['errors']) > 0 : 0;
$has_success_message = isset($_SESSION['messages']['success']) ? count($_SESSION['messages']['success']) > 0 : 0;


$invoices = Invoice::all();
$table_rows = "";
$current_row = 0;
$last_row = count($invoices);
foreach($invoices as $invoice){
  $current_row++;
  $customer_full_name = $invoice->customer->first_name.' '.$invoice->customer->last_name;
  if($has_success_message && ($current_row == $last_row)){
    $table_rows.="<tr class='new_row'>";
  }else{
    $table_rows.="<tr>";
  }
  $table_rows.="<td>$customer_full_name</td>  
                <td>$invoice->sent_date</td>
                <td>$invoice->due_date</td>
                <td>$invoice->total</td>
                <td>$invoice->web_link</td>
                <td class='action_buttons'>
                  <a class='action_button' href='/businessManager/Controllers/delete_invoice.php?invoice_id=$invoice->invoice_id'>Delete</a> <!-- | 
                  <a class='action_button' href='#'>Edit</a> -->
                </td>
              </tr>";
}

// Type is a string. it should be set to either "errors" or "success"
function print_message($type){ 
  foreach($_SESSION['messages'][$type] as $message){
    echo "<h3 class='user_message_text'> $message </h3>";
  }
  $_SESSION['messages'][$type] = [];
}
?>
<main>
  <div class="user_message">
    <?php 
    if($has_error_message){   
      print_message('errors');
    }
    if($has_success_message){
      print_message('success');
    }
    ?>
  </div>

  <div class="table_container">
    <table>
      <caption class="table_title"><h1>Invoices</h1></caption>
      <thead>
        <tr class="no-hover">
          <th scope="col">Customer name</th>
          <th scope="col">Sent date</th>
          <th scope="col">Due date</th>
          <th scope="col">Invoice Total</th>
          <th scope="col">Web link</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php echo $table_rows; ?>
      </tbody>
    </table>
  </div>
</main>
<?php 
$page->render_footer();
?>