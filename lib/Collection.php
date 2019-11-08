<?php

class Collection {
    function __construct($name, $db = "") {
      $this->name = $name;
    }

    // TODO Not so nice
    function query($sql) {
      global $cx;
        $db = $cx->db;
      
      return $db->query($sql);
    }

    // function tableExist($table) {
    //   $sql = "SELECT name FROM sqlite_master WHERE type='table' AND name='$table'";

    //   $ret = $this->query($sql);
    //   return $ret->fetchArray(SQLITE3_ASSOC);
    // }

    function getAll(){
      $sql = "SELECT * from $this->name;";

      $ret = $this->query($sql);

      // Get All
      $array = [];
      while($row = $ret->fetchArray(SQLITE3_ASSOC) ) {
         $array[] = $row;
      }
     return $array;
    }

    function findById($id){
      $sql ="SELECT * from $this->name WHERE id=$id;";

      $ret = $this->query($sql);
      return $ret->fetchArray(SQLITE3_ASSOC);
    }

    function insert($insert) {
      
      $keys= array_keys($insert);
      $values = array_values($insert);
      foreach ($values as &$value) {
        // $value = "'$value'";
        $value = "'".SQLite3::escapeString($value)."'";
      }
       
      $keys = join(',',$keys);
      $values = join(',',$values);

      // $sql = "INSERT INTO $this->name (ID,NAME,AGE,ADDRESS,SALARY) VALUES (1, 'Paul', 32, 'California', 20000.00 );";
      $sql = "INSERT INTO $this->name ($keys) VALUES ($values);";
      echo $sql;
      return $ret = $this->query($sql);
    }

    function updateById($id, $doc) {
      foreach ($doc as $key => $value) {
        $value = $this->escapeString($value);
        $set[] = "$key = '$value'";
      }

      $set = join(',',$set);
      
      // $sql = "UPDATE table_name SET column1 = value1, column2 = value2 WHERE id = $id;";
      $sql = "UPDATE $this->name SET $set WHERE id = $id;";
      // echo $sql;

      $query = $this->query($sql);
      if (!$query) {
        exit("Error in query: $sql");
      } else {
          // echo 'Number of rows modified: ', $dbhandle->changes();
      }
    }

    function deleteById($id) {
      $sql = "DELETE FROM $this->name WHERE id = $id;";
      // echo $sql;

      $query = $this->query($sql);
      if (!$query) {
        exit("Error in query: $sql");
      } else {
          // echo 'Number of rows modified: ', $dbhandle->changes();
      }
    }
 }
