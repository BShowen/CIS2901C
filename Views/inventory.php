<?php 
require __DIR__."/../Models/Page.php";
$page = new Page();

$has_error_message = isset($_SESSION['messages']['errors']) ? count($_SESSION['messages']['errors']) > 0 : 0;
$has_success_message = isset($_SESSION['messages']['success']) ? count($_SESSION['messages']['success']) > 0 : 0;

$inventory_items = InventoryItem::all();
$last_row = count($inventory_items);
$current_row = 0;
$table_rows = "";
foreach($inventory_items as $inventory_item){
  $current_row++;
  if($has_success_message && ($current_row == $last_row)){
    $table_rows.= "<tr class='new_row'>";
  }else{
    $table_rows.= "<tr>";
  }
  $in_stock = ($inventory_item->in_stock == 1) ? "Yes" : "No" ;
  $table_rows.="  <td class='item_name'>$inventory_item->item_name</td>
    <td class='item_description'>$inventory_item->item_description</td>
    <td class='in_stock'>$in_stock</td>
    <td class='stock_level'>$inventory_item->stock_level</td>
    <td class='price'>$inventory_item->price</td>
    <td class='action_buttons'>
      <a class='action_button' href='/businessManager/Controllers/delete_inventory_item.php?item_id=$inventory_item->item_id'>Delete</a> <!-- |
      <a class='action_button' href='#'>Edit</a> -->
    </td>
  </tr>";
}

// type is a string. it should be set to either "errors" or "success"
function print_message($type){ 
  foreach($_SESSION['messages'][$type] as $message){
    echo "<h3 class='user_message_text'> $message </h3>";
  }
  $_SESSION['messages'][$type] = [];
}
?>
<main>
  <div class="user_message">
    <?php 
    if($has_error_message){   
      print_message('errors');
    }
    if($has_success_message){
      print_message('success');
    }
    ?>
  </div>

  <div class="table_container">
    <table>
      <caption class="table_title"><h1>Inventory</h1></caption>
      <thead>
        <tr class="no-hover">
          <th scope="col">Item name</th>
          <th scope="col">Item Description</th>
          <th scope="col">In stock</th>
          <th scope="col">Stock level</th>
          <th scope="col">Price</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php echo $table_rows; ?>
      </tbody>
    </table>
  </div>

  <div class="show_form_button">
    <button class="show_form collapsed">New inventory item</button>
  </div>

  <div class="form_container">
    <form  action="/businessManager/Controllers/new_inventory_item.php" method="POST">
      <div class="form_title">
        <h1>New inventory item</h1>
      </div>
      <div class="grid_container">
        
        <div class="grid_item_label">
          <label for="item_name">Item name</label>
        </div>
        <div class="grid_item_input">
          <input type="text" id="item_name" name="item_name">
        </div>

        <div class="grid_item_label">
          <label for="item_description">Item description</label>
        </div>
        <div class="grid_item_input">
          <textarea id="item_description" name="item_description"></textarea>
        </div>

        <div class="grid_item_label">
          <label for="in_stock">In Stock</label>
        </div>
        <div class="grid_item_input">
          <select name="in_stock" id="in_stock">
            <option value="1">Yes</option>
            <option value="0">No</option>
          </select>
        </div>

        <div class="grid_item_label">
          <label for="stock_level">Stock level</label>
        </div>
        <div class="grid_item_input">
          <input type="number" id="stock_level" name="stock_level" min="0" step="1">
        </div>  

        <div class="grid_item_label">
          <label for="price">Price</label>
        </div>
        <div class="grid_item_input">
          <input type="number" id="price" name="price" min="0" step=".01">
        </div>

        <div class="grid_item_input">
          <button type="submit">Submit</button>
        </div>
      </div>
    </form>
  </div>
</main>
<?php 
$page->render_footer();
?>