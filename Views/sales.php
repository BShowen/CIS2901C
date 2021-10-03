<?php 
require __DIR__."/../Models/Page.php";
$page = new Page();

$db = new Database();

// This section of code is used when the user is being redirected to this page and there is a status message in the session. 
// This happens when the user uses the form on this page to create a new sale or deletes a sale. The form is submitted, a
// database connection and SQL statement are executed, this page is re-rendered with a status in the session. 
$user_message = isset($_SESSION['user_message']);
$message = '';
if($user_message){
  $message = $_SESSION['user_message'];
  $_SESSION['user_message'] = null;
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
$table_rows = "";
if($result[0]){
  $result = $result[1];
  $last_row = $result->num_rows;
  $current_row = 0;
  while ($row = $result->fetch_assoc()) {
    $current_row++;
    extract($row);
    if(str_contains($message, 'added') && ($current_row == $last_row)){
      $table_rows.="<tr class='new_row clickable' data-id='$sale_id'>
                    <td>$sales_person</td>
                    <td>$customer</td>
                    <td>$sale_total</td>
                    <td>$sale_date</td>
                    <td class='action_buttons'>
                        <a class='delete_button' href='/businessManager/Controllers/delete_sale.php?sale_id=$sale_id'>Delete</a> | 
                        <a class='edit_button' data-id='$sale_id' href='#'>Edit</a>
                    </td>
                  </tr>";
    }else{
      $table_rows.="<tr class='clickable' data-id='$sale_id'>
                      <td>$sales_person</td>
                      <td>$customer</td>
                      <td>$sale_total</td>
                      <td>$sale_date</td>
                      <td class='action_buttons'>
                        <a class='delete_button' href='/businessManager/Controllers/delete_sale.php?sale_id=$sale_id'>Delete</a> | 
                        <a class='edit_button' data-id='$sale_id' href='#'>Edit</a>
                      </td>
                    </tr>"; 
    }
  }
}

// This query is responsible for retrieving the list of customers from the database. 
// This list is used in the form to allow the user to pick out the customer when they're creating a new sale. 
$customer_query = "SELECT customer_id, first_name, last_name FROM Customers";
$customer_results = $db->execute_sql_statement($customer_query);
$customer_selection_list = "";
if($customer_results[0]){
  $result = $customer_results[1];
  while ($row = $result->fetch_assoc()){
    extract($row);
    $customer_selection_list .= "<option value=$customer_id>$first_name $last_name</option>";
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
      <caption class="table_title"><h1>Sales</h1></caption>
      <thead>
        <tr>
          <th scope="col">Sales person</th>
          <th scope="col">Customer</th>
          <th scope="col">Sale total</th>
          <th scope="col">Sale date</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php echo $table_rows; ?>
      </tbody>
    </table>
  </div>

  <div class="show_form_button">
    <button class="show_form collapsed">New Sale</button>
  </div>

  <div class="form_container">
    <form action="/businessManager/Controllers/new_sale.php" method="POST">
      <div class="form_title">
        <h1>New Sale</h1>
      </div>
      <div class="grid_container">
        <div class="grid_item_label">
          <label for="customer">Customer</label>
        </div>
        <div class="grid_item_input">
          <select name="customer_id" id="customer">
            <?php echo $customer_selection_list; ?>
          </select>
        </div>

        <div class="grid_item_label">
          <label for="total">Sale total</label>
        </div>
        <div class="grid_item_input">
          <input type="number" id="total" name="sale_total" min="0" step=".01">
        </div>

        <div class="grid_item_label">
          <label for="date">Sale date</label>
        </div>
        <div class="grid_item_input">
          <input type="date" id="date" name="sale_date">
        </div>

        <div class="grid_item_input">
          <button type="submit">Submit</button>      
        </div>

      </div>
    </form>
  </div>
</main>
<?php 
$page->render_footer();
?>