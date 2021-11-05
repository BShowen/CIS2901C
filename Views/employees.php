<?php 
require __DIR__."/../globalFunctions.php";
require __DIR__."/../Models/Message.php";
require __DIR__."/../Models/Page.php";
$page = new Page();

$employees = Employee::all();
$last_row = count($employees);
$current_row = 0;
$table_rows = "";
$admin_user = current_logged_in_employee()->is_admin;

$id1 = intval(current_logged_in_employee()->employee_id);
$id2 = intval($_COOKIE['employee_id']);
echo $id1 == $id2 ? "true" : "false";


if(!empty($employees)){
  foreach($employees as $employee){
    $current_row++;
    if(($current_row == $last_row) && table_has_new_row()){
      $table_rows.="<tr class='new_row clickable' data-href='/businessManager/Views/employee.php?employee_id=$employee->employee_id'>";
    }else{
      $table_rows.="<tr class='clickable' data-href='/businessManager/Views/employee.php?employee_id=$employee->employee_id'>";
    } 
    if($admin_user){
      $table_rows.="<td>$employee->first_name</td>
        <td>$employee->last_name</td>
        <td>$employee->user_name</td>
        <td>$employee->email_address</td>
        <td class='action_buttons'>";
        // If the current user is this user, then don't show a delete button. Users cant delete themselves this way. 
        if(intval(current_logged_in_employee()->employee_id) != intval($employee->employee_id)){
          $table_rows.="<a class='action_button' href='/businessManager/Controllers/delete_employee.php?employee_id={$employee->employee_id}'>Delete</a>
            </td>
          </tr>";
        }
    }else{
      $table_rows.="<td>$employee->first_name</td>
        <td>$employee->last_name</td>
        <td>$employee->user_name</td>
        <td>$employee->email_address</td>
        
      </tr>";
    }
  }
}
?>

<main>
 
  <div class="notification_container">
    <?php require __DIR__.'/partials/_user_message.php'?>
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
          <?php if($admin_user){
            echo "<th scope='col'>Action</td>";
          }?>
        </tr>
      </thead>
      <tbody>
        <?php echo $table_rows; ?>
      </tbody>
    </table>
  </div>

  <?php if($admin_user){ ?>
    <div class="show_form_button">
      <button class="show_form collapsed">New employee</button>
    </div>

    <div class="form_container">
      <form action="/businessManager/Controllers/new_employee.php" method="POST">
        <div class="form_title">
          <h1>New employee</h1>
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
            <label for="street_address">User name</label>
          </div>
          <div class="right_container">
            <input type="text" id="user_name" name="user_name">
          </div>

          <div class="left_container">
            <label for="city">Email address</label>
          </div>
          <div class="right_container">
            <input type="text" id="email_address" name="email_address">
          </div>

          <div class="left_container">
            <label for="city">Password</label>
          </div>
          <div class="right_container">
            <input type="password" id="temp_password" name="temp_password">
          </div>

          <div class="left_container">
            <label for="is_admin">Employee is admin?</label>
          </div>
          <div class="right_container"> 
            <select id="is_admin" name="is_admin">
              <option value="1">Yes</option>
              <option value="0">No</option>
            </select>
          </div>

          <div class="right_container">
            <button type="submit">Save</button>
          </div>
        </div>
      </form>
    </div>
  <?php } ?>

</main>
<?php 
$page->render_footer();
?>