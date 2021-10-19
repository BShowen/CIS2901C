<?php 
require __DIR__."/../Models/Page.php";
$page = new Page();

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
?>
<main>
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
</main>
<?php 
$page->render_footer();
?>