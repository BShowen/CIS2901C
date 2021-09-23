<?php 
  function document_title(){
    $title = basename($_SERVER['SCRIPT_FILENAME'], '.php');
    return $title == 'Index' ? 'Dashboard' : $title ;
  } 
  $title = document_title();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- <link rel="stylesheet" href="./../Assets/Stylesheets/css_reset.css"> -->
  <link rel="stylesheet" href="./../Assets/Stylesheets/style.css">
  <script src=<?php echo "./../Assets/JavaScript/{$title}.js" ?> ></script>
  <title><?php echo ucfirst($title); ?></title>
</head>
<body>
  <nav>
    <ul>
      <li><a href="./index.php">Dashboard</a></li>
      <li><a href="./employees.php">Employees</a></li>
      <li><a href="./inventory.php">Inventory</a></li>
      <li><a href="./customers.php">Customers</a></li>
      <li><a href="./sales.php">Sales</a></li>
      <li><a href="./invoices.php">Invoices</a></li>
    </ul>
  </nav>
