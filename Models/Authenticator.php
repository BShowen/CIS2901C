<?php 
class Authenticator {
  private $db;
  private $user_name;
  private $password;
  private $errors = [];
  
  public function __construct($user_name, $password){
    $this->db = new Database(); 
    $this->set_user_name($user_name);
    $this->set_password($password);
    if(isset($this->user_name) && isset($this->password)){
      $this->login();
    }
  }
  
  private function set_user_name($user_name){
    $user_name = trim($user_name);
    if(strlen($user_name) > 0){
      $this->user_name = $user_name;
    }else{
      array_push($this->errors, "User name is required.");
    }
  }
  
  private function set_password($password){
    $password = trim($password);
    if(strlen($password) > 0){
      $this->password = $password;
    }else{
      array_push($this->errors, "Password is required.");
    }
  }

  private function user_exists(){
    return $this->db->exists(['user_name'=>$this->user_name], "Employees");
  }

  private function login(){
    if($this->user_exists()){
      $query = "SELECT employee_id, password_digest FROM Employees WHERE user_name = ?";
      $params = ['user_name'=>$this->user_name];
      $results = $this->db->execute_sql_statement($query, $params);
      if($results[0]){
        $results = $results[1];
        $row = $results->fetch_assoc();
        extract($row);
      }
      if(password_verify($this->password, $password_digest)){
        setcookie('current_user', $employee_id, 0, "/");  
        $this->redirect_to('dashboard');
      }
    }
  
    array_push($this->errors, "Incorrect user name or password.");
  }

  private function redirect_to($page){
    echo "redirect";
    Header("Location: http://".$_SERVER['HTTP_HOST']."/businessManager/Views/$page.php");
  }

  public function get_errors(){
    return $this->errors;
  }
}
?>