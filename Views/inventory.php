<?php 
require __DIR__."/../Models/Page.php";
$page = new Page();
?>
<main>
  <h1>Inventory</h1>
</main>
<?php 
$page->render_footer();
?>