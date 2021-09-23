<?php 
require __DIR__."/../Models/Page.php";
$page = new Page();

$db = new Database();

$query = "SELECT * FROM Customers";
$result = $db->execute_sql_statement($query);
$table_rows = "";
if($result[0]){
  $result = $result[1];
  while ($row = $result->fetch_assoc()) {
    extract($row);
    $table_rows.="
    <tr class='table_row' id='$customer_id'>
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
  }
}

// This section of code is used when the user is being redirected to this page and there is a status message in the session. 
// This happens when the user uses the form on this page to create a new customer or deletes a customer. The form is submitted, a
// database connection and SQL statement are executed, this page is re-rendered with a status in the session. 
$user_message = isset($_SESSION['user_message']) ? $_SESSION['user_message'][0] : 0 ;

if($user_message){
  $message = $_SESSION['user_message'][1];
  $_SESSION['user_message'] = null;
}

?>
<main>
  <div class="user_message">
    <?php if($user_message){ ?>
      <h3 class="user_message_text"><?php echo $message ?></h3>
    <?php } ?>
  </div>

  <div class="customer_table_container">
    <table class="table">
      <caption class="table_title"><h1>Customers</h1></caption>
      <thead>
        <tr>
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

  <div class="new_customer_form form_container">
    <form class="form" action="/businessManager/Controllers/new_customer.php" method="POST">
      <h1 class="form_title">New customer</h1>
      <div class="form_controls">
        <label for="first_name">First name</label>
        <input type="text" id="first_name" name="first_name">

        <label for="last_name">Last name</label>
        <input type="text" id="last_name" name="last_name">

        <label for="street_address">Street address</label>
        <input type="text" id="street_address" name="street_address">

        <label for="city">City</label>
        <input type="text" id="city" name="city">

        <label for="state">State</label>
        <input type="text" id="state" name="state">

        <label for="zip">Zip</label>
        <input type="text" id="zip" name="zip">

        <input type="submit">
      </div>
    </form>
  </div>
  <button class="show_form collapsed">New customer</button>
</main>
<?php 
$page->render_footer();
?>