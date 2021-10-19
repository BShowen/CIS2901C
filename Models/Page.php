<?php
// Require in the script for connecting to the database. This is done here so that every 
// Page has access to the database if they need it. 
require __DIR__."/Database.php";
require __DIR__."/Business.php";
require __DIR__."/InventoryItem.php";
require __DIR__."/Employee.php";
require __DIR__."/Customer.php";
require __DIR__."/Sale.php";
require __DIR__."/SaleItem.php";
require __DIR__."/Invoice.php";
class Page {

  public function __construct(){
    // Render the header, regardless of the status of the current user. 
    $this->render_header();

    if(isset($_COOKIE['employee_id'])){
      $current_employee = Employee::find_by_id(intval($_COOKIE['employee_id']));
    }
    
    if(isset($current_employee) && $current_employee->authenticated && basename($_SERVER['SCRIPT_FILENAME'], '.php') == 'index'){
      header("Location: http://".$_SERVER['HTTP_HOST']."/businessManager/Views/dashboard.php");
    }else if(!isset($current_employee)){
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