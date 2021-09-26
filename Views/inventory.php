<?php 
require __DIR__."/../Models/Page.php";
$page = new Page();

$db = new Database();

// This section of code is used when the user is being redirected to this page and there is a status message in the session. 
// This happens when the user uses the form on this page to create a new inventory item or deletes an inventory item. The form is submitted, a
// database connection and SQL statement are executed, this page is re-rendered with a status in the session. 
$user_message = isset($_SESSION['user_message']);
$message = '';
if($user_message){
  $message = $_SESSION['user_message'];
  $_SESSION['user_message'] = null;
}

$query = "SELECT * FROM Inventory_items";
$result = $db->execute_sql_statement($query);
$table_rows = "";
if($result[0]){
  $result = $result[1];
  $last_row = $result->num_rows;
  $current_row = 0;
  while ($row = $result->fetch_assoc()) {
    $current_row++;
    extract($row);
    $in_stock = $in_stock ? "True" : "False";
    if(str_contains($message, 'added') && ($current_row == $last_row)){
      $table_rows.= "<tr class='new_row'>
                      <td class='item_name'>$item_name</td>
                      <td class='item_description'>$item_description</td>
                      <td class='in_stock'>$in_stock</td>
                      <td class='stock_level'>$stock_level</td>
                      <td class='price'>$price</td>
                      <td class='action_buttons'>
                        <a class='delete_button' href='/businessManager/Controllers/delete_inventory_item.php?item_id=$item_id'>Delete</a> |
                        <a class='edit_button' href='#'>Edit</a>
                      </td>
                    </tr>";
    }else{
      $table_rows.= "<tr>
                      <td class='item_name'>$item_name</td>
                      <td class='item_description'>$item_description</td>
                      <td class='in_stock'>$in_stock</td>
                      <td class='stock_level'>$stock_level</td>
                      <td class='price'>$price</td>
                      <td class='action_buttons'>
                        <a class='delete_button' href='/businessManager/Controllers/delete_inventory_item.php?item_id=$item_id'>Delete</a> |
                        <a class='edit_button' href='#'>Edit</a>
                      </td>
                    </tr>";
    }
  }
}
?>
<main>
  <div class="user_message">
    <?php if($user_message){ ?>
      <h3 class="user_message_text"><?php echo $message ?></h3>
    <?php } ?>
  </div>

  <div class="inventory_table_container">
    <table class="table">
      <caption class="table_title"><h1>Inventory</h1></caption>
      <thead>
        <tr>
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

  <button class="show_form collapsed">New inventory item</button>

  <div class="inventory_form form_container">
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