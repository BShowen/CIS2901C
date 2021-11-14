<div class="card">
  <div class="card_title">
    <h1>Item details</h1>
  </div>

  <div class="card_details">
    <form action="/businessManager/Controllers/update_inventory_item.php" method="POST">
      <div class="grid_container">

        <div class="left_container">
        </div> 
        <div class="right_container">
          <?php $item_id= $item->item_id; ?>
          <input type="text" id="item_id" name="item_id" value="<?php echo $item_id; ?>" hidden >
        </div>
      
        <div class="left_container">
          <p>Item name</p>
        </div> 
        <div class="right_container">
          <?php $item_name = $item->item_name; ?>
          <input type="text" id="item_name" name="item_name" placeholder="<?php echo $item_name; ?>" >
        </div>
        <hr>

        <div class="left_container">
          <p>Item description</p>
        </div> 
        <div class="right_container">
          <?php $item_description = $item->item_description; ?>
          <input type="text" id="item_description" name="item_description" placeholder="<?php echo $item_description; ?>" >
        </div>
        <hr>

        <div class="left_container">
          <p>Stock level</p>
        </div> 
        <div class="right_container">
          <p><?php $stock_level = $item->stock_level; ?></p>
          <input type="number" id="stock_level" name="stock_level" step="1" min="0" placeholder="<?php echo $stock_level; ?>" >
        </div>
        <hr>
        
        <div class="left_container">
          <p>Price</p>
        </div> 
        <div class="right_container">
          <p><?php $price = $item->price_formatted; ?></p>
          <input type="number" id="price" name="price" step="0.01" min="0" placeholder="<?php echo $price; ?>" >
        </div>
        <hr>

        <div class="left_container">
          <button type="button" id="cancel">Cancel</button>
        </div>
        <div class="right_container">
          <button type="submit">Save</button>
        </div>
      </div>
    </form>
  </div>
</div>