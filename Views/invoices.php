<?php 
require __DIR__."/../Models/Page.php";
$page = new Page();

$db = new Database();

// This section of code is used when the user is being redirected to this page and there is a status message in the session. 
// This happens when the user uses the form on this page to create a new invoice or deletes a invoice. The form is submitted, a
// database connection and SQL statement are executed, this page is re-rendered with a status in the session. 
$user_message = isset($_SESSION['user_message']);
$message = '';
if($user_message){
  $message = $_SESSION['user_message'];
  $_SESSION['user_message'] = null;
}

$query = "SELECT 
          concat(C.first_name, ' ', C.last_name) AS customer_name, 
          I.sent_date, 
          I.due_date, 
          I.total, 
          I.web_link, 
          I.invoice_id
          FROM Customers C JOIN Invoices I USING (customer_id)";
$result = $db->execute_sql_statement($query);
$table_rows = "";
if($result[0]){
  $result = $result[1];
  $last_row = $result->num_rows;
  $current_row = 0;
  while ($row = $result->fetch_assoc()) {
    $current_row++;
    extract($row);
    if(str_contains($message, 'added') && ($current_row == $last_row)){
      $table_rows.="<tr class='new_row'>
                      <td>$customer_name</td>  
                      <td>$sent_date</td>
                      <td>$due_date</td>
                      <td>$total</td>
                      <td>$web_link</td>
                      <td class='action_buttons'>
                        <a class='delete_button' href='/businessManager/Controllers/delete_invoice.php?invoice_id=$invoice_id'>Delete</a> | 
                        <a class='edit_button' href='#'>Edit</a>
                    </td>
                    </tr>";
    }else{
      $table_rows.="<tr>
                      <td>$customer_name</td>  
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
  }
}

// This query is responsible for retrieving the list of customers from the database. 
// This list is used in the form to allow the user to pick out the customer when they're creating a new invoice. 
$customer_query = "SELECT customer_id, first_name, last_name FROM Customers";
$customer_results = $db->execute_sql_statement($customer_query);
$customer_list = "";
if($customer_results[0]){
  $result = $customer_results[1];
  while ($row = $result->fetch_assoc()){
    extract($row);
    $customer_list .= "<option value=$customer_id>$first_name $last_name</option>";
  }
}

//Query for selecting sales person, customer, and sales date.
$query = "SELECT 
          concat(E.first_name, ' ', E.last_name) AS 'sales_person', 
          concat(C.first_name, ' ', C.last_name) AS customer, 
          S.sale_total, 
          S.sale_date,
          S.sale_id
          FROM Sales AS S JOIN Customers AS C USING (customer_id)
          JOIN Employees AS E USING (employee_id)";
$result = $db->execute_sql_statement($query);
$sales_selection_list = "";
if($result[0]){
  $result = $result[1];
  $last_row = $result->num_rows;
  $current_row = 0;
  while ($row = $result->fetch_assoc()) {
    extract($row);
    $sales_selection_list .= "<option value=$sale_id>$customer - Balance $sale_total</option>";
  }
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
      <tbody>
        <tr>
          <th scope="col">Customer name</th>
          <th scope="col">Sent date</th>
          <th scope="col">Due date</th>
          <th scope="col">Invoice Total</th>
          <th scope="col">Web link</th>
          <th scope="col">Action</th>
        </tr>
        <?php echo $table_rows; ?>
      </tbody>
    </table>
  </div>

  <div class="show_form_button">
    <button class="show_form collapsed">New invoice</button>
  </div>

  <div class="form_container">
    <form action="/businessManager/Controllers/new_invoice.php" method="POST">
      <div class="form_title">
        <h1>New invoice</h1>
      </div>
      <div class="grid_container">
        <div class="grid_item_label">
          <label for="customer">Customer</label>
        </div>
        <div class="grid_item_input">
          <select name="customer_id" id="customer">
            <?php echo $customer_list; ?>
          </select>
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
</main>
<?php 
$page->render_footer();
?>