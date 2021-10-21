<?php 
class Database{
  private $db = null;

  private $connection_status = False;

  // In the case of errors while executing an SQL statement, the message will be 
  // store in this array. 
  private $error_message = [];

  // This array is referenced when this class's 'execute_sql_statement' is called. 
  // These are the only type of sql parameters allowed. Change the allowed types 
  // by adding their names here. The names in this array are taken from the list of 
  // possible return values from php's gettype() function. 
  private const ACCEPTED_SQL_TYPES = ['integer', 'double', 'string'];

  // This stores the last inserted id. 
  private $last_inserted_id;

  function __construct(){
    // Require in the config file that holds all of the sensitive data for connecting to mySQL. 
    require __DIR__.'/../.myConfig.php';
    try{
      $this->db = new mysqli($CONFIG['HOST'], $CONFIG['USER_NAME'], $CONFIG['PASSWORD'], $CONFIG['DB_NAME']); 
      if(mysqli_connect_errno()){
        throw new Error('There was an error connecting to the DB.<br/>Check your environment vars.');
      }else{
        $this->connection_status = True;
      }
    }catch(Error $e){
      echo $e->getMessage().'<br/>';
      exit();
    }
  }

  // This function attempts to execute a provided SQL statement against the database. 
  // Always returns a two element array. First element is true/false indicating success
  // or failure of SQL statement. Second element is the results of a SELECT statement or
  // NULL for all other statements. 
  // $sql_statement is an sql statement passed in as a string. 
  // $sql_parameters are the parameters required for the sql statement, and is 
  // passed in as an array as ['name'=>'FooBar', 'age'=>33];
  // Example call: execute_sql_statement("SELECT * FROM users WHERE id > ?", ["id"=> 5]);
  public function execute_sql_statement($sql_statement, $sql_parameters = null){  
    $results = [False, null]; //This variable is what will be returned from this method.
    if($this->connection_status){
      $query = $sql_statement;
      $stmt = ($this->db)->prepare($query);
      if($sql_parameters && $this->validate_sql_parameters($sql_parameters)){
        $stmt->bind_param(
          $this->param_types($sql_parameters), 
          ...array_values($sql_parameters)
        );
      }
      $stmt->execute();
      // If the $sql_statement was a SELECT statement then true clause is initiated. 
      // Else the False clause is initiated. 
      if($sql_statement[0] == "S"){
        $result = $stmt->get_result();
        // Set the $results to reflect whether or not the SQL statement was a success
        if($result->num_rows > 0){
          $results = [True, $result];
        } else {
          return $results;
        }
      }else{
        $stmt->store_result();
        $this->last_inserted_id = $stmt->insert_id;
        // Set the $results to reflect whether or not the SQL statement was a success
        $results[0] = True;
      } 
    }
    return $results;
  }

  public function __get($name){
    return $this->$name;
  }

  // This function queries the database and returns true or false depending if the row exists. 
  // Call this function by passing in an associative array and table name. 
  // Here is an example function call to see if the "username" column of the "Employees" table contains a username "FooBar" 
  // $user_exists = exists(['user_name'=>'FooBar'], 'Employees');
  public function exists($param, $table){
    $param_name = array_keys($param)[0];
    $query = "SELECT EXISTS(SELECT * FROM $table WHERE $param_name = ?) AS 'exists' ";
    $results = $this->execute_sql_statement($query,$param);
    $result = $results[1];
    $row = $result->fetch_assoc();
    return $row['exists'] == 1;
  }


  // This function ia called by $this->execute_sql_statement. 
  // The caller sends the sql parameters to this function in order for them to be 
  // validated. $sql_parameters is an array in the format ['a'=>'b', 'c'=>1]. 
  // This function calls gettype() on each value and refers to this class's constant
  // 'ACCEPTED_SQL_TYPES' and checks to see if the gettype() return value is listed
  // in the 'accepted_sql_parameters' constant. Returns a boolean. 
  private function validate_sql_parameters($sql_parameters){
    $values = array_values($sql_parameters);
    foreach($values as $value){
      if(!in_array(gettype($value), self::ACCEPTED_SQL_TYPES)){
        return False;
      }
    }
    return true;
  }

  // This function is called by $this->execute_sql_statement. 
  // The caller provides the sql parameters for their types to be determined for 
  // the $stmt->bind() function. Returns a string of datatype abbreviations that is 
  // accepted by bind().
  // For example, $params = ['A'=>1, 'B'=>2.0, 'C'=>"Bradley"];
  // param_types($params) returns the string "ids". 
  private function param_types($sql_parameters){
    $values = array_values($sql_parameters);
    $param_types = '';
    foreach($values as $value){
      $param_types.=gettype($value)[0];
    }
    return $param_types;
  }

  // This function will delete a row from a database. This function is called only after all checks for child records have 
  // been performed. In other words, this function should never fail. This function is reached after the object being deleted 
  // has checked for children records and found zero children records. Otherwise this function is not reached. 
  // params must be ['column_name'=>column_value]
  // table_name must be a string of the exact table name in the database. 
  // Database->delete(['user_id'=>10], 'Users');
  public function delete($params, $table_name){
    $column_name = $column_name = array_keys($params)[0];
    $query = "DELETE FROM $table_name WHERE $column_name = ?";
    $results = $this->execute_sql_statement($query, $params);
    return $results[0];
  }


  // ['user_id'=>int, 'user_name'=>String, 'etc'=>etc];
  public function update($params, $table_name){
    $query = $this->build_update_query($params, $table_name);
    // The next lines of code are reordering the $params so that they match with the query parameters. 
    // The parameters need to be in lexical order with the query parameters. 
    $attribute_names = array_keys($params);
    $primary_key_name = $attribute_names[0];
    $primary_key_value = $params[$primary_key_name];
    unset($params[$primary_key_name]);
    $params[$primary_key_name] = $primary_key_value;
    return $this->execute_sql_statement($query, $params);
  }

  private function build_update_query($params, $table_name){
    $attribute_names = array_keys($params);
    $query = "UPDATE $table_name SET ";
    for($i = 1; $i < count($attribute_names); $i++){
      if($i + 1 == count($params)){
        $query .= "$attribute_names[$i] = ? ";
      }else{
        $query .= "$attribute_names[$i] = ?, ";
      }
    }
    $primary_key = $attribute_names[0];
    $query .= "WHERE $primary_key = ?";
    return $query;
  }
}
?>
