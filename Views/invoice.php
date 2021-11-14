<?php 
require __DIR__."/../Models/Message.php";
require __DIR__."/../globalFunctions.php";
require __DIR__."/../Models/Page.php";
$page = new Page();

define('INVOICE_ID', intval($_GET['invoice_id']));
$invoice = Invoice::find_by_id(INVOICE_ID);
$edit_invoice_details = isset($_GET['edit']) ? boolval($_GET['edit']) : 0 ; 
?>

<main>
  <div class="notification_container">
    <?php display_session_messages(); ?>
  </div>
    
  <?php   
    if($edit_invoice_details){
      require __DIR__."/partials/invoice_page/_invoice_details_edit.php";
    }else{
      require __DIR__."/partials/invoice_page/_invoice_details.php";
    }
  ?>
</main>