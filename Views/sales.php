<?php 
require __DIR__."/../Models/Page.php";
$page = new Page();

$db = new Database();

//Query for selecting sales person, customer, and sales date.
$query = "SELECT 
  concat(E.first_name, ' ', E.last_name) AS 'sales_person', 
  concat(C.first_name, ' ', C.last_name) AS customer, 
  S.sale_total, 
  S.sale_date
  FROM Sales AS S JOIN Customers AS C USING (customer_id)
  JOIN Employees AS E USING (employee_id)
";

$result = $db->execute_sql_statement($query);
$table_rows = "";
if($result[0]){
  $result = $result[1];
  while ($row = $result->fetch_assoc()) {
    extract($row);
    $table_rows.="<tr>
    <td>$sales_person</td>
    <td>$customer</td>
    <td>$sale_total</td>
    <td>$sale_date</td>
    </tr>";
  }
}
?>
<main>
  <div class="table">
    <table>
      <caption><h1>Sales</h1></caption>
      <tbody>
        <tr>
          <th scope="col">Sales person</th>
          <th scope="col">Customer</th>
          <th scope="col">Sale total</th>
          <th scope="col">Sale date</th>
        </tr>
        <?php echo $table_rows; ?>
      </tbody>
    </table>
  </div>
</main>
<?php 
$page->render_footer();
?>