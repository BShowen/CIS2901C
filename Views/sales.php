<?php 
require __DIR__."/../globalFunctions.php";
require __DIR__."/../Models/Message.php";
require __DIR__."/../Models/Page.php";
$page = new Page();

$sales = Sale::all();
$last_row = count($sales);
$current_row = 0;
$table_rows = "";
foreach($sales as $sale){
  $current_row++;
  if( ($current_row == $last_row) && table_has_new_row() ){
    $table_rows.="<tr class='new_row' data-href='/businessManager/Views/sale.php?sale_id=$sale->sale_id'>";
  }else{
    $table_rows.="<tr data-href='/businessManager/Views/sale.php?sale_id=$sale->sale_id'>";
  }
  $sales_person = $sale->sales_person->first_name;
  $customer = $sale->customer;
  $table_rows.="<td>$sales_person</td>
    <td>$customer->first_name</td>
    <td>$sale->sale_total</td>
    <td>$sale->sale_date</td>
    <td class='action_buttons'>
      <a class='action_button' href='/businessManager/Controllers/delete_sale.php?sale_id=$sale->sale_id'>Delete</a> <!-- | 
      <a class='action_button' data-id='$sale->sale_id' href='#'>Edit</a> -->
    </td>
  </tr>"; 
}

// This query is responsible for retrieving the list of customers from the database. 
// This list is used in the form to allow the user to pick out the customer when they're creating a new sale. 
$customers = Customer::all();
$customer_selection_list = "";
foreach($customers as $customer){
  $customer_full_name = $customer->first_name." ".$customer->last_name;
  $customer_selection_list .= "<option value=$customer->customer_id>$customer_full_name</option>";
}
?>
<main>
  <div class="user_message">
    <?php display_session_messages(); ?>
  </div>

  <div class="table_container">
    <table>
      <caption class="table_title"><h1>Sales</h1></caption>
      <thead>
        <tr class="no-hover">
          <th scope="col">Sales person</th>
          <th scope="col">Customer</th>
          <th scope="col">Sale total</th>
          <th scope="col">Sale date</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php echo $table_rows; ?>
      </tbody>
    </table>
  </div>

  <div class="show_form_button">
    <button class="show_form collapsed">New Sale</button>
  </div>

  <div class="form_container">
    <form action="/businessManager/Controllers/new_sale.php" method="POST">
      <div class="form_title">
        <h1>New Sale</h1>
      </div>
      <div class="grid_container">
        <div class="left_container">
          <label for="customer">Customer</label>
        </div>
        <div class="right_container">
          <select name="customer_id" id="customer">
            <?php echo $customer_selection_list; ?>
          </select>
        </div>

        <div class="left_container">
          <label for="total">Sale total</label>
        </div>
        <div class="right_container">
          <input type="number" id="total" name="sale_total" min="0" step=".01">
        </div>

        <div class="left_container">
          <label for="date">Sale date</label>
        </div>
        <div class="right_container">
          <input type="date" id="date" name="sale_date">
        </div>

        <div class="right_container">
          <button type="submit">Submit</button>      
        </div>

      </div>
    </form>
  </div>
</main>
<?php 
$page->render_footer();
?>