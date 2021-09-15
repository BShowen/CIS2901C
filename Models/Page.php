<?php
class Page {

  public function __construct(){
    $this->render_header();
  }
  
  public function render_header(){
    require __DIR__."/../Views/header.php";
  }

  public function render_footer(){
    require __DIR__."/../Views/footer.php";
  }

}
?>