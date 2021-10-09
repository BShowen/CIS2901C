<?php 
interface CRUDInterface {
  
  public static function all();

  public static function find_by_id($id);

  public function save();

  public function delete();
}
?>