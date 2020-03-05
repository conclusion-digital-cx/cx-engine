<?php

$service = require("../service.php");
$all = $service("pages")->getAll();
print_r($all);