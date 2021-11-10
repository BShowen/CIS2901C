<?php 
require __DIR__."/../Models/Message.php";
require __DIR__."/../globalFunctions.php";
require __DIR__."/../Models/Page.php";
$page = new Page();

define('EMPLOYEE_ID', intval($_GET['employee_id']));
$employee = Employee::find_by_id(EMPLOYEE_ID);
$sales = $employee->sales;
$create_invoice = isset($_GET['new_invoice']) ? intval($_GET['new_invoice']) : 0 ;
$invoices_requested = isset($_GET['sale_id']) && !$create_invoice;
$sales_table_rows = "";
$invoice_table_rows = "";
$edit_employee_details = isset($_GET['edit']) ? boolval($_GET['edit']) : 0 ;
 
// Create table rows for each sale.
forEach($sales as $sale){
  $sales_table_rows .= "
  <tr>
    <!--class='clickable' 
    data-href='/businessManager/Views/employee.php?employee_id={$employee->employee_id}&sale_id=$sale->sale_id' data-id='$sale->sale_id'-->
    <td class='sale_number'>$sale->sale_id</td>
    <td class='sales_person'>{$sale->sales_person->first_name}</td>
    <td class='sale_total'>$sale->sale_total</td>
    <td class='sale_date'>$sale->sale_date</td>
  </tr>";
}

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
    if($edit_employee_details){
      require __DIR__."/partials/employee_page/_employee_details_edit.php";
    }else{
      require __DIR__."/partials/employee_page/_employee_details.php";
    }
  
    require __DIR__.'/partials/employee_page/_employee_sales.php';
  
    // if($invoices_requested){ 
    //   require __DIR__.'/partials/employee_page/_invoice.php'; 
    // }
  ?>
</main>