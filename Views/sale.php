<?php 
require __DIR__."/../Models/Message.php";
require __DIR__."/../globalFunctions.php";
require __DIR__."/../Models/Page.php";
$page = new Page();

define('SALE_ID', intval($_GET['sale_id']));
$sale = Sale::find_by_id(SALE_ID);
// $invoices = $sale->invoices;
// $create_invoice = isset($_GET['new_invoice']) ? intval($_GET['new_invoice']) : 0 ;
// $invoices_requested = isset($_GET['sale_id']) && !$create_invoice;
// $invoices_table_rows = "";
// $invoice_table_rows = "";
$edit_sale_details = isset($_GET['edit']) ? boolval($_GET['edit']) : 0 ;
 
// Create table rows for each sale.
// forEach($invoices as $invoice){
//   $invoices_table_rows .= "
//   <tr 
//     class='clickable' 
//     data-href='/businessManager/Views/invoice.php?invoice_id={$invoice->invoice_id}'>
//     <td>$invoice->sale_id</td>
//     <td>{$invoice->sales_person->first_name}</td>
//     <td>$invoice->sale_total</td>
//     <td>$invoice->sale_date</td>
//     <td>
//       <a class='action_button' href='/businessManager/Controllers/delete_sale.php?sale_id=$invoice->sale_id'>Delete</a>
//       <a class='action_button' data-id='$invoice->customer_id' href='/businessManager/Views/customer.php?customer_id={$invoice->customer_id}&sale_id={$invoice->sale_id}&new_invoice=1'>Create invoice</a>
//     </td>
//   </tr>";
// }

// If there are invoices then create a table row for each invoice. 
// if($invoices_requested){
//   $sale_id = intval($_GET['sale_id']);
//   $sale = Sale::find_by_id($sale_id);
//   $invoices = $sale->invoices;
//   if(!empty($invoices)){
//     forEach($invoices as $invoice){
//       $invoice_table_rows.="<tr>
//                               <td>$sale->sale_id</td>
//                               <td>$invoice->invoice_id</td>
//                               <td>$invoice->sent_date</td>
//                               <td>$invoice->due_date</td>
//                               <td>$invoice->total</td>
//                               <td>$invoice->web_link</td>
//                               <td class='action_buttons'>
//                               <a class='action_button' href='/businessManager/Controllers/delete_invoice.php?invoice_id=$invoice->invoice_id'>Delete</a> 
//                               </td>
//                             </tr>";
//     }
//   }else{
//     $invoices_requested = False;
//     $messages = [];
//     foreach($sale->errors as $error_message){
//       array_push($messages, new Message("error", $error_message));
//     }
//     set_session_messages($messages);
//   }
// }
?>

<main>
  <div class="notification_container">
    <?php display_session_messages(); ?>
  </div>
    
  <?php   
    if($edit_sale_details){
      require __DIR__."/partials/sale_page/_sale_details_edit.php";
    }else{
      require __DIR__."/partials/sale_page/_sale_details.php";
    }
  
    // if(count($sale->sales) > 0){
    //   require __DIR__.'/partials/customer_page/_customer_sales.php';
    // }else{
    //   echo "<div style='text-align:center;'><h3>This customer does not have any sales.</h3></div>";
    // }
  
    // if($invoices_requested){ 
    //   require __DIR__.'/partials/customer_page/_invoice.php'; 
    // }

    // if($create_invoice){
    //   require __DIR__.'/partials/customer_page/_new_invoice_form.php';
    // }
  ?>
</main>