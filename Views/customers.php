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
    $table_rows.="<tr>
    <td>$first_name</td>
    <td>$last_name</td>
    <td>$street_address</td>
    <td>$city</td>
    <td>$state</td>
    <td>$zip</td>
    </tr>";
  }
}

// This section of code is used when the user is being redirected to this page and there is a status message in the session. 
// This happens when the user uses the form on this page to create a new customer. The form is submitted, a database connection
// and SQL statement is execute, this page is re-rendered with a status in the session. 
$new_customer_status = False;
if($_SESSION['NEW_CUSTOMER_CONTROLLER_RESPONSE']){
  $new_customer_status = $_SESSION['NEW_CUSTOMER_CONTROLLER_RESPONSE'];
  $_SESSION['NEW_CUSTOMER_CONTROLLER_RESPONSE'] = null;
}

?>
<main>
  <div class="new_customer_message">
    <?php if($new_customer_status){ ?>
      <h3 class="new_customer_message_text">Customer successfully added</h3>
    <?php } ?>
  </div>

  <div class="table">
    <table>
      <caption><h1>Customers</h1></caption>
      <tbody>
        <tr>
          <th scope="col">First name</th>
          <th scope="col">Last name</th>
          <th scope="col">Street Address</th>
          <th scope="col">City</th>
          <th scope="col">State</th>
          <th scope="col">Zip</th>
        </tr>
        <?php echo $table_rows; ?>
      </tbody>
    </table>
  </div>

  <div class="new_customer_form">
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
</main>
<?php 
$page->render_footer();
?>