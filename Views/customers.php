<?php 
require __DIR__."/../Models/Page.php";
$page = new Page();

$db = new Database();

// This section of code is used when the user is being redirected to this page and there is a status message in the session. 
// This happens when the user uses the form on this page to create a new customer or deletes a customer. The form is submitted, a
// database connection and SQL statement are executed, this page is re-rendered with a status in the session. 
$user_message = isset($_SESSION['user_message']);
$message = '';
if($user_message){
  $message = $_SESSION['user_message'];
  $_SESSION['user_message'] = null;
}

$query = "SELECT * FROM Customers";
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
      $table_rows.="<tr class='new_row clickable' data-id='$customer_id'>
                      <td class='first_name'>$first_name</td>
                      <td class='last_name'>$last_name</td>
                      <td class='street_address'>$street_address</td>
                      <td class='city'>$city</td>
                      <td class='state'>$state</td>
                      <td class='zip'>$zip</td>
                      <td class='action_buttons'>
                        <a class='delete_button' href='/businessManager/Controllers/delete_customer.php?customer_id=$customer_id'>Delete</a> | 
                        <a class='edit_button' data-id='$customer_id' href='#'>Edit</a>
                      </td>
                    </tr>";
    }else{
      $table_rows.="<tr class='clickable' data-id='$customer_id'>
                      <td class='first_name'>$first_name</td>
                      <td class='last_name'>$last_name</td>
                      <td class='street_address'>$street_address</td>
                      <td class='city'>$city</td>
                      <td class='state'>$state</td>
                      <td class='zip'>$zip</td>
                      <td class='action_buttons'>
                        <a class='delete_button' href='/businessManager/Controllers/delete_customer.php?customer_id=$customer_id'>Delete</a> | 
                        <a class='edit_button' data-id='$customer_id' href='/businessManager'>Edit</a>
                      </td>
                    </tr>";
    } 
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
      <caption class="table_title"><h1>Customers</h1></caption>
      <thead>
        <tr class="no-hover">
          <th scope="col">First name</th>
          <th scope="col">Last name</th>
          <th scope="col">Street Address</th>
          <th scope="col">City</th>
          <th scope="col">State</th>
          <th scope="col">Zip</th>
          <th scope="col">Action</th>
        </tr> 
      </thead>
      <tbody>
        <?php echo $table_rows; ?>
      </tbody>
    </table>
  </div>

  <div class="show_form_button">
    <button class="show_form collapsed">New customer</button>
  </div>

  <div class="form_container">
    <form action="/businessManager/Controllers/new_customer.php" method="POST">
      <div class="form_title">
        <h1>New customer</h1>
      </div>
      <div class="grid_container">
        <div class="grid_item_label">
          <label for="first_name">First name</label>
        </div>
        <div class="grid_item_input">
          <input type="text" id="first_name" name="first_name">
        </div>

        <div class="grid_item_label">
          <label for="last_name">Last name</label>
        </div>
        <div class="grid_item_input">
          <input type="text" id="last_name" name="last_name">
        </div>

        <div class="grid_item_label">
          <label for="street_address">Street address</label>
        </div>
        <div class="grid_item_input">
          <input type="text" id="street_address" name="street_address">
        </div>

        <div class="grid_item_label">
          <label for="city">City</label>
        </div>
        <div class="grid_item_input">
          <input type="text" id="city" name="city">
        </div>

        <div class="grid_item_label">
          <label for="state">State</label>
        </div>
        <div class="grid_item_input">
          <input type="text" id="state" name="state">
        </div>

        <div class="grid_item_label">
          <label for="zip">Zip</label>
        </div>
        <div class="grid_item_input">
          <input type="text" id="zip" name="zip">
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