<?php 
require __DIR__."/../Models/Page.php";
$page = new Page();

$db = new Database();

$query = "SELECT first_name, last_name, user_name, email_address FROM Employees";
$result = $db->execute_sql_statement($query);
$table_rows = "";
if($result[0]){
  $result = $result[1];
  while ($row = $result->fetch_assoc()) {
    extract($row);
    $table_rows.="<tr>
    <td>$first_name</td>
    <td>$last_name</td>
    <td>$user_name</td>
    <td>$email_address</td>
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