<?php 
require __DIR__."/../globalFunctions.php";
require __DIR__."/../Models/Message.php";
require __DIR__."/../Models/Page.php";
$page = new Page();

$customers = Customer::all();
$last_row = count($customers);
$current_row = 0;
$table_rows = "";
foreach($customers as $customer){
  $current_row++;
  if(($current_row == $last_row) && table_has_new_row()){
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
      <a class='action_button' href='/businessManager/Controllers/delete_customer.php?customer_id=$customer->customer_id'>Delete</a>
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
        <div class="left_container">
          <label for="first_name">First name</label>
        </div>
        <div class="right_container">
          <input type="text" id="first_name" name="first_name">
        </div>

        <div class="left_container">
          <label for="last_name">Last name</label>
        </div>
        <div class="right_container">
          <input type="text" id="last_name" name="last_name">
        </div>

        <div class="left_container">
          <label for="street_address">Street address</label>
        </div>
        <div class="right_container">
          <input type="text" id="street_address" name="street_address">
        </div>

        <div class="left_container">
          <label for="city">City</label>
        </div>
        <div class="right_container">
          <input type="text" id="city" name="city">
        </div>

        <div class="left_container">
          <label for="state">State</label>
        </div>
        <div class="right_container">
          <input type="text" id="state" name="state">
        </div>

        <div class="left_container">
          <label for="zip">Zip</label>
        </div>
        <div class="right_container">
          <input type="text" id="zip" name="zip">
        </div>

        <div class="right_container">
          <input type="submit">
        </div>
      </div>
    </form>
  </div>
</main>
<?php 
$page->render_footer();
?>