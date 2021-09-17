<?php

// Require in the script for connecting to the database. This is done here so that every 
// Page has access to the database if they need it. 
require __DIR__."/Database.php";
class Page {

  public function __construct(){
    // if current user is logged in
      $this->render_header();
    // else if user is NOT logged in and they are trying to a view a page other than the login page.
    // In other words. A non logged in user can view the log in page ONLY. 
      // Redirect to login page.
  }
  
  public function render_header(){
    require __DIR__."/../Views/header.php";
  }

  public function render_footer(){
    require __DIR__."/../Views/footer.php";
  }

}
?>