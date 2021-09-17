<?php 
require __DIR__."/../Models/Page.php";
$page = new Page();

$db = new Database();

$query = "SELECT 
  concat(C.first_name, ' ', C.last_name) AS customer_name, 
  I.sent_date, 
  I.due_date, 
  I.total, 
  I.web_link 
  FROM Customers C JOIN Invoices I USING (customer_id)
";
$result = $db->execute_sql_statement($query);
$table_rows = "";
if($result[0]){
  $result = $result[1];
  while ($row = $result->fetch_assoc()) {
    extract($row);
    $table_rows.="<tr>
    <td>$customer_name</td>  
    <td>$sent_date</td>
    <td>$due_date</td>
    <td>$total</td>
    <td>$web_link</td>
    </tr>";
  }
}
?>
<main>
  <div class="table">
    <table>
      <caption><h1>Invoices</h1></caption>
      <tbody>
        <tr>
          <th scope="col">Customer name</th>
          <th scope="col">Sent date</th>
          <th scope="col">Due date</th>
          <th scope="col">Invoice Total</th>
          <th scope="col">Web link</th>
        </tr>
        <?php echo $table_rows; ?>
      </tbody>
    </table>
  </div>
</main>
<?php 
$page->render_footer();
?>