<?php 
require __DIR__."/../Models/Page.php";
$page = new Page();

$db = new Database();

$customer_id = intval($_GET['id']);
?>

<main>
  <p>Customer page. Customer is = <?php  echo $customer_id; ?></p>
</main>