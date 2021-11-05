<div class="card">
  <div class="card_title">
    <h1>Item details</h1>
  </div>

  <div class="card_details">
    <div class="grid_container">
      <div class="left_container">
        <p>Item name</p>
      </div> 
      <div class="right_container">
        <p><?php echo $item->item_name; ?></p>
      </div>
      <hr>

      <div class="left_container">
        <p>Item description</p>
      </div> 
      <div class="right_container">
        <p><?php echo $item->item_description; ?></p>
      </div>
      <hr>
      
      <div class="left_container">
        <p>In stock?</p>
      </div> 
      <div class="right_container">
        <p><?php echo $item->in_stock ? "Yes" : "No" ; ?></p>
      </div>
      <hr>
      
      <div class="left_container">
        <p>Stock level</p>
      </div> 
      <div class="right_container">
        <p><?php echo $item->stock_level; ?></p>
      </div>
      <hr>

      <div class="left_container">
        <p>Price</p>
      </div>
      <div class="right_container">
        <p><?php echo $item->price; ?></p>
      </div>
      <hr>

      <div class="left_container">
        <button id="edit_details" data-url="<?php echo $_SERVER['REQUEST_URI']."&edit=1" ?>">Edit</button>
      </div>

    </div>
  </div>
</div>
