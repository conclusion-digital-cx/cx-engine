<?php

class CxDb extends SQLite3 {

    public $version = "1.0.0";

    function __construct($path = 'test.db') {
       error_log("Conneting to $path");
       $this->open($path);
    }

    // Simple ORM
    // function getInformation() {
    //   $sql = "SELECT * FROM sqlite_master";

    //   $ret = @$this->query($sql);
    //   return $ret->fetchArray(SQLITE3_ASSOC);
    // }

    function tableExist($table) {
      $sql = "SELECT name FROM sqlite_master WHERE type='table' AND name='$table'";

      $ret = $this->query($sql);
      // return $ret;
      return $ret->fetchArray(SQLITE3_ASSOC);
    }

    function getAll($from){
      $sql = "SELECT * from $from;";

      $ret = $this->query($sql);

      // Get All - PHP can be a bit silly
      $array = [];
      while($row = $ret->fetchArray(SQLITE3_ASSOC) ) {
         $array[] = $row;
      }
     return $array;
    }

    function findById($from, $id){
      $sql ="SELECT * from $from WHERE id=$id;";

      $ret = $this->query($sql);
      return $ret->fetchArray(SQLITE3_ASSOC);
    }

    function insert($from, $insert) {
      $keys= array_keys($insert);
      $values = array_values($insert);
      foreach ($values as &$value) {
        // $value = "'$value'";
        $value = "'".SQLite3::escapeString($value)."'";
      }  
       
      $keys = join(',',$keys);
      $values = join(',',$values);

      // $sql = "INSERT INTO $from (ID,NAME,AGE,ADDRESS,SALARY) VALUES (1, 'Paul', 32, 'California', 20000.00 );";
      $sql = "INSERT INTO $from ($keys) VALUES ($values);";
      $ret = $this->query($sql);
      return $this->lastInsertRowid();
    }

    function updateById($from, $id, $doc) {
      foreach ($doc as $key => $value) {
        $value = $this->escapeString($value);
        $set[] = "$key = '$value'";
      }

      $set = join(',',$set);
      
      // $sql = "UPDATE table_name SET column1 = value1, column2 = value2 WHERE id = $id;";
      $sql = "UPDATE $from SET $set WHERE id = $id;";
      // echo $sql;

      $query = $this->query($sql);
      // if (!$query) {
      //   exit("Error in query: $sql");
      // } else {
      //     // echo 'Number of rows modified: ', $dbhandle->changes();
      // }
      return $query;
    }

    function deleteById($from, $id) {
      $sql = "DELETE FROM $from WHERE id = $id;";
      // echo $sql;

      $query = $this->query($sql);
      if (!$query) {
        exit("Error in query: $sql");
      } else {
          // echo 'Number of rows modified: ', $dbhandle->changes();
      }
    }
 }


// $db = new CxDb();
// if(!$db) {
//     echo $db->lastErrorMsg();
// } else {
//     echo "Opened database successfully\n";
// }
