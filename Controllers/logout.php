<?php 
if(isset($_COOKIE['current_user'])){
  setcookie('current_user', '', 0, "/");
  unset($_COOKIE['current_user']);
  session_reset();
}
Header('Location: http://'.$_SERVER['HTTP_HOST'].'/businessManager/index.php');
?>