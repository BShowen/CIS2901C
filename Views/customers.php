<?php 
require __DIR__."/../Models/Page.php";
$page = new Page();

$has_error_message = isset($_SESSION['messages']['errors']) ? count($_SESSION['messages']['errors']) > 0 : 0;
$has_success_message = isset($_SESSION['messages']['success']) ? count($_SESSION['messages']['success']) > 0 : 0;

$customers = Customer::all();
$last_row = count($customers);
$current_row = 0;
$table_rows = "";
foreach($customers as $customer){
  $current_row++;
  if($has_success_message && ($current_row == $last_row)){
    $table_rows.="<tr class='new_row clickable' data-href='/businessManager/Views/customer.php?customer_id=$customer->customer_id'>";
  }else{
    $table_rows.="<tr class='clickable' data-href='/businessManager/Views/customer.php?customer_id=$customer->customer_id'>";
  } 
  $table_rows .= "<td class='first_name'>$customer->first_name</td>
    <td class='last_name'>$customer->last_name</td>
    <td class='street_address'>$customer->street_address</td>
    <td class='city'>$customer->city</td>
    <td class='state'>$customer->state</td>
    <td class='zip'>$customer->zip</td>
    <td class='action_buttons'>
      <a class='action_button' href='/businessManager/Controllers/delete_customer.php?customer_id=$customer->customer_id'>Delete</a> <!-- | 
       <a class='action_button' data-id='$customer->customer_id' href='/businessManager'>Edit</a> -->
    </td>
  </tr>";
}


// type is a string. it should be set to either "errors" or "success"
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