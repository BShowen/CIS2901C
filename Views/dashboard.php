<?php
require __DIR__."/../Models/Page.php";
$page = new Page();

$employees = Employee::all();
$employee_rows = '';
foreach($employees as $employee){
  $employee_name = $employee->first_name.' '.$employee->last_name;
  $total_sales = count($employee->sales);
  $revenue = floatval(0.00);
  foreach($employee->sales as $sale){
    $revenue += floatval($sale->sale_total);
  }
  $employee_rows.="<tr>
                    <td>$employee_name</td>
                    <td>$total_sales</td>
                    <td>\${$revenue}</td>
                  </tr>";
}

$customers = Customer::all();
$customer_rows = '';
foreach($customers as $customer){
  $customer_name = $customer->first_name.' '.$customer->last_name;
  $total_sales = count($customer->sales);
  $revenue = floatval(0.00);
  foreach($customer->sales as $sale){
    $revenue += floatval($sale->sale_total);
  }
  $customer_rows.="<tr>
                    <td>$customer_name</td>
                    <td>$total_sales</td>
                    <td>\${$revenue}</td>
                  </tr>";
}

?>
<main>
  <div class="notification_container">
    <?php display_session_messages(); ?>
  </div> 
  
  <div style="width: 100%;text-align:center;">
    <h1>Dashboard</h1>
  </div>

  <div class="card">
    <div class="card_title">
      <h1>Employees</h1>
    </div>

    <div class="card_details">  
      <table>
        <thead>
          <tr class="no-hover">
            <th scope="col">Name</th>
            <th scope="col">Number of sales</th>
            <th scope="col">Total sale revenue</th>
          </tr> 
        </thead>
        <tbody> 
          <?php echo $employee_rows; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="card">
    <div class="card_title">
      <h1>Customers</h1>
    </div>

    <div class="card_details">  
      <table>
        <thead>
          <tr class="no-hover">
            <th scope="col">Name</th>
            <th scope="col">Number of sales</th>
            <th scope="col">Total sale revenue</th>
          </tr> 
        </thead>
        <tbody> 
          <?php echo $customer_rows; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>
<?php 
$page->render_footer();
?>