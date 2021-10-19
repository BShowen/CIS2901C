<?php 
setcookie('business_id', '', 0, "/");
setcookie('employee_id', '', 0, "/");
setcookie('authenticated', '', 0, "/");
session_reset();
Header('Location: http://'.$_SERVER['HTTP_HOST'].'/businessManager/index.php');
?>