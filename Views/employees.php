<?php 
require __DIR__."/../Models/Page.php";
$page = new Page();

$has_error_message = isset($_SESSION['messages']['errors']) ? count($_SESSION['messages']['errors']) > 0 : 0;
$has_success_message = isset($_SESSION['messages']['success']) ? count($_SESSION['messages']['success']) > 0 : 0;

$employees = Employee::all();
$table_rows = "";
if(!empty($employees)){
  foreach($employees as $employee){
    $table_rows.="<tr>
    <td>$employee->first_name</td>
    <td>$employee->last_name</td>
    <td>$employee->user_name</td>
    <td>$employee->email_address</td>
    </tr>";
  }
}

// This variable is used to determine if the form for adding new users can be rendered. 
$current_user_is_admin = Employee::find_by_id(intval($_COOKIE['employee_id']))->is_admin;

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

  <?php if($current_user_is_admin){ ?>
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