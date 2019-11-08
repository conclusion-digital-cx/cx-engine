<?php

require_once "../lib/Collection.php";

$collection = new Collection("trips");

?>
<div class="topnav">
    <?php foreach($collection->getAll() as $row) { ?>
        <a href="<?=$row['url']?>">
            <?=$row['title']?>
        </a>
    <?php } ?>
</div>
