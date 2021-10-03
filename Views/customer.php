<?php 
require __DIR__."/../Models/Page.php";
$page = new Page();

$db = new Database();


$query = "SELECT * FROM Customers WHERE customer_id = ?";
$params = ['id' => intval($_GET['id'])];
$result = $db->execute_sql_statement($query, $params);
if($result[0]){
  $result = ($result[1])->fetch_assoc();
  extract($result);
}
?>

<main>
  <div class="customer_card">
    <h1><?php echo $first_name." ".$last_name; ?></h1>
    <p><?php echo $street_address;?></p>
    <p><?php echo $city;?></p>
    <p><?php echo $state; ?></p>
    <p><?php echo $zip; ?></p>
  </div>

  <!-- <div class="customer_sales">
    <p>Customer sales here</p>
  </div>

  <div class="customer_invoices">
    <p>Customer invoices here</p>
  </div> -->
</main>