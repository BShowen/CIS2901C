<?php 
require_once __DIR__.'/../globalFunctions.php';

$employee_name = current_logged_in_employee()->first_name.' '.current_logged_in_employee()->last_name;
$admin = current_logged_in_employee()->is_admin ? "Admin" : "Not Admin";
?>
<div class="nav_container">
  <div class="nav_top_container">
    <nav>
      <ul>
        <li class="nav_button_container"><a class="nav_button" href="/businessManager/Views/dashboard.php">Dashboard</a></li>
        <li class="nav_button_container"><a class="nav_button" href="/businessManager/Views/employees.php">Employees</a></li>
        <li class="nav_button_container"><a class="nav_button" href="/businessManager/Views/inventory.php">Inventory</a></li>
        <li class="nav_button_container"><a class="nav_button" href="/businessManager/Views/customers.php">Customers</a></li>
        <li class="nav_button_container"><a class="nav_button" href="/businessManager/Views/sales.php">Sales</a></li>
        <li class="nav_button_container"><a class="nav_button" href="/businessManager/Views/invoices.php">Invoices</a></li>
        <li class="nav_button_container"><a class="nav_button" href="/businessManager/Controllers/logout.php">Log out</a></li>
        <!-- <li class="nav_user_details"><p><?php echo $employee_name."<br/>".$admin?></p></li> -->
      </ul>
    </nav>
  </div>
    
  <div class="nav_bottom_container">
    <div class="nav_user_details">
      <p><?php echo $employee_name."<br/>".$admin?></p>
    </div>
  </div>  
</div>