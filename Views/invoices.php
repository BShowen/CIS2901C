<?php 
require __DIR__."/../Models/Page.php";
require __DIR__."/../Models/Invoices.php";

$page = new Page();
$db = new Database();
$invoices = new Invoices();

// This section of code is used when the user is being redirected to this page and there is a status message in the session. 
// This happens when the user uses the form on this page to create a new invoice or deletes a invoice. The form is submitted, a
// database connection and SQL statement are executed, this page is re-rendered with a status in the session. 
$user_message = isset($_SESSION['user_message']);
$message = '';
if($user_message){
  $message = $_SESSION['user_message'];
  $_SESSION['user_message'] = null;
}


$result = $invoices->get_all_invoices();
$table_rows = "";
$current_row = 0;
$last_row = count($result);
foreach($result as $row){
  $current_row++;
  extract($row);
  if(str_contains($message, 'added') && ($current_row == $last_row)){
    $table_rows.="<tr class='new_row'>";
  }else{
    $table_rows.="<tr>";
  }
  $table_rows.="<td>$customer_name</td>  
                <td>$sent_date</td>
                <td>$due_date</td>
                <td>$total</td>
                <td>$web_link</td>
                <td class='action_buttons'>
                  <a class='delete_button' href='/businessManager/Controllers/delete_invoice.php?invoice_id=$invoice_id'>Delete</a> | 
                  <a class='edit_button' href='#'>Edit</a>
                </td>
              </tr>";
}
?>
<main>
  <div class="user_message">
    <?php if($user_message){ ?>
      <h3 class="user_message_text"><?php echo $message ?></h3>
    <?php } ?>
  </div>

  <div class="table_container">
    <table>
      <caption class="table_title"><h1>Invoices</h1></caption>
      <thead>
        <tr>
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