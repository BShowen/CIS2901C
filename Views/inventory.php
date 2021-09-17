<?php 
require __DIR__."/../Models/Page.php";
$page = new Page();

$db = new Database();

$query = "SELECT * FROM Inventory_items";
$result = $db->execute_sql_statement($query);
$table_rows = "";
if($result[0]){
  $result = $result[1];
  while ($row = $result->fetch_assoc()) {
    extract($row);
    $table_rows.="<tr>
    <td>$item_name</td>
    <td>$item_description</td>
    <td>$price</td>
    <td>$in_stock</td>
    <td>$stock_level</td>
    </tr>";
  }
}
?>
<main>
  <div class="table">
    <table>
      <caption><h1>Inventory</h1></caption>
      <tbody>
        <tr>
          <th scope="col">Item name</th>
          <th scope="col">Item Description</th>
          <th scope="col">Price</th>
          <th scope="col">In stock</th>
          <th scope="col">Stock level</th>
        </tr>
        <?php echo $table_rows; ?>
      </tbody>
    </table>
  </div>
</main>
<?php 
$page->render_footer();
?>