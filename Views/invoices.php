<?php 
require __DIR__."/../globalFunctions.php";
require __DIR__."/../Models/Message.php";
require __DIR__."/../Models/Page.php";

$page = new Page();

$invoices = Invoice::all();
$table_rows = "";
foreach($invoices as $invoice){
  $customer_full_name = $invoice->customer->first_name.' '.$invoice->customer->last_name;
  $table_rows.="<tr>
                  <td>$customer_full_name</td>  
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
?>
<main>
  <div class="user_message">
    <?php display_session_messages(); ?>
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