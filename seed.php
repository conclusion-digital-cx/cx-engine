<?php

// Example 
include "lib/Cx.php";

$cx = new Cx();

echo "<p>Installing...</p>";
seedDb($cx->db);

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
      echo "<p>View <a href='/'>website</a></p>\n";
   } else {
      echo "<p>Table created successfully</p>\n";
      echo "<p>View <a href='/'>website</a></p>\n";
   }
}
