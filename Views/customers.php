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
?>
<main>
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
</main>
<?php 
$page->render_footer();
?>