<?php

// Example 
include "lib/CxEngine.php";

$cx = new CxEngine();

seedDb($cx->db);
echo "Installed...";

// ========

function seedDb($db) {
    $sql =<<<EOF
    CREATE TABLE PAGES
    (
        ID INT PRIMARY KEY     NOT NULL,
        URL           TEXT    NOT NULL
    );
EOF;

   $ret = $db->exec($sql);
   if(!$ret){
      echo $db->lastErrorMsg();
   } else {
      echo "Table created successfully\n";
   }
}

    // CREATE TABLE BLOCKS
    // (
    //     ID INT PRIMARY KEY     NOT NULL,
    //     NAME           TEXT    NOT NULL,
    // );