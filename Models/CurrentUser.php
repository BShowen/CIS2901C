<?php
class CurrentUser{

  private $logged_in = False;

  public function __construct(){
    $user_id = $this->get_user_id();
    if($user_id >= 0){
      $this->logged_in = True;
    }
  }

  private function get_user_id(){
    if(isset($_COOKIE['current_user'])){
      return $_COOKIE['current_user'];
    }else{
      return -1;
    }
  }

  public function is_logged_in(){
    return $this->logged_in;
  }
}
?>