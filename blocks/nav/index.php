<?php

require_once "lib/Collection.php";

$collection = new Collection("menus");

?>
<div class="topnav">
    <a class="active" href="/home">Home</a>
    <?php foreach($collection->getAll() as $row) { ?>
        <a href="<?=$row['url']?>"><?=$row['title']?></a>
    <?php } ?>

    <a style="float:right" href="/admin">Admin</a>

    <?php if(isset($_GET['editor'])) { ?>
        <a style="float:right" href="<?=str_replace("?editor","",$_SERVER['REQUEST_URI'])?>">Disable editor</a>
    <?php } else { ?>
        <a style="float:right" href="?editor">Enable editor</a>
    <?php } ?>
</div>
