<?php 
require __DIR__."/../globalFunctions.php";
require __DIR__."/../Models/Message.php";
require __DIR__."/../Models/Page.php";
$page = new Page();

$employees = Employee::all();
$last_row = count($employees);
$current_row = 0;
$table_rows = "";
if(!empty($employees)){
  foreach($employees as $employee){
    $current_row++;
    if(($current_row == $last_row) && table_has_new_row()){
      $table_rows.="<tr class='new_row'>";
    }else{
      $table_rows.="<tr>";
    } 
    $table_rows.="<td>$employee->first_name</td>
      <td>$employee->last_name</td>
      <td>$employee->user_name</td>
      <td>$employee->email_address</td>
    </tr>";
  }
}
?>

<main>
  <div class="user_message">
    <?php display_session_messages(); ?>
  </div>
  
  <div class="table_container">
    <table>
      <caption class="table_title"><h1>Employees</h1></caption>
      <thead>
        <tr class="no-hover">
          <th scope="col">First name</th>
          <th scope="col">Last name</th>
          <th scope="col">User name</th>
          <th scope="col">Email address</th>
        </tr>
      </thead>
      <tbody>
        <?php echo $table_rows; ?>
      </tbody>
    </table>
  </div>

  <?php if(current_logged_in_employee()->is_admin){ ?>
    <div class="show_form_button">
      <button class="show_form collapsed">New employee</button>
    </div>

    <div class="form_container">
      <form action="/businessManager/Controllers/new_employee.php" method="POST">
        <div class="form_title">
          <h1>New employee</h1>
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
            <label for="street_address">User name</label>
          </div>
          <div class="grid_item_input">
            <input type="text" id="user_name" name="user_name">
          </div>

          <div class="grid_item_label">
            <label for="city">Email address</label>
          </div>
          <div class="grid_item_input">
            <input type="text" id="email_address" name="email_address">
          </div>

          <div class="grid_item_label">
            <label for="city">Password</label>
          </div>
          <div class="grid_item_input">
            <input type="password" id="temp_password" name="temp_password">
          </div>

          <div class="grid_item_label">
            <label for="is_admin">Employee is admin?</label>
          </div>
          <div class="grid_item_input"> 
            <select id="is_admin" name="is_admin">
              <option value="0">No</option>
              <option value="1">Yes</option>
            </select>
          </div>

          <div class="grid_item_input">
            <input type="submit">
          </div>
        </div>
      </form>
    </div>
  <?php } ?>

</main>
<?php 
$page->render_footer();
?>