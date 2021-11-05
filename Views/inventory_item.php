<?php 
require __DIR__."/../Models/Message.php";
require __DIR__."/../globalFunctions.php";
require __DIR__."/../Models/Page.php";
$page = new Page();

define('ITEM_ID', intval($_GET['item_id']));
$item = InventoryItem::find_by_id(ITEM_ID);
$edit_item_details = isset($_GET['edit']) ? boolval($_GET['edit']) : 0 ;
?>

<main>
  <?php 
    require __DIR__.'/partials/_user_message.php';

    if($edit_item_details){
      require __DIR__."/partials/inventory_page/_item_details_edit.php";
    }else{
      require __DIR__."/partials/inventory_page/_item_details.php";
    }
  
  ?>
</main>