<?php
session_start();
// Require in the script for connecting to the database. This is done here so that every 
// Page has access to the database if they need it. 
require __DIR__."/Database.php";

require __DIR__.'/CurrentUser.php';
class Page {

  public function __construct(){
    // Render the header, regardless of the status of the current user. 
    $this->render_header();
    $current_user = new CurrentUser();
    if($current_user->is_logged_in() && basename($_SERVER['SCRIPT_FILENAME'], '.php') == 'index'){
      header("Location: http://".$_SERVER['HTTP_HOST']."/businessManager/Views/dashboard.php");
    }else if(!$current_user->is_logged_in()){
      $this->redirect_to_login();
    }else{
      $this->render_nav();
    }
  }
  
  private function render_header(){
    require __DIR__."/../Views/header.php";
  }

  public function render_footer(){
    require __DIR__."/../Views/footer.php";
  }

  private function render_nav(){
    require __DIR__."/../Views/navigation.php";
  }

  private function redirect_to_login(){
    // Redirect only if the page being displayed is NOT the index page. In other words, if the page being displayed is the index
    // page then there is no need to redirect. 
    if( basename($_SERVER['SCRIPT_FILENAME'], '.php') != 'index'){
      header("Location: http://".$_SERVER['HTTP_HOST']."/businessManager/index.php");
    }
  }

}
?>