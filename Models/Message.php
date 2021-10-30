<?php 

class Message{
  private $type;
  private $message;

  public function __construct($type, $message){
    $this->type = strtolower($type);
    $this->message = $message;
  }

  public function __toString(){
    switch($this->type){
      case "error":
        return "<h3 class='user_message_text'> $this->message </h3>";
        break;
      case "success":
        return "<h3 class='user_message_text'> $this->message </h3>";
        break;
      default:
        return "Invalid error type";
    }
  }

}

?>