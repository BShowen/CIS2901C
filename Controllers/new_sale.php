<?php
require __DIR__.'/../globalFunctions.php';
require __DIR__.'/../Models/Message.php';
require __DIR__.'/../Models/Sale.php';

$params = get_filtered_post_params();
$params['customer_id'] = intval($params['customer_id']);
$params['sale_total'] = doubleval($params['sale_total']);
$params['employee_id'] = intval($_COOKIE['employee_id']);
$sale = new Sale($params);
$messages = [];
if($sale->save()){
  array_push( $messages, new Message("success", "Sale successfully added") );
  table_has_new_row(true);
}else{
  $errors = $sale->errors;
  foreach($errors as $error_message){
    array_push( $messages, new Message("error", $error_message) );
  }
}

set_session_messages($messages);

redirect_to("sales");
?>