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
  <link rel="stylesheet" href="/businessManager/Assets/Stylesheets/style.css">
  <script src="/businessManager/Assets/JavaScript/toggle_form.js"></script>
  <title><?php echo ucfirst($title); ?></title>
</head>
<body>
