<?php
require_once __DIR__."/../lib/Collection.php";
$collection = new Collection("news");
?>

<div class="">
    <?php foreach($collection->getAll() as $row) { ?>
        <!-- <a href="<?=$row['url']?>"><?=$row['title']?></a> -->

        <div class="card" style="width: 18rem;">
            <!-- <img src="..." class="card-img-top" alt="..."> -->
            <div class="card-body">
                <h5 class="card-title"><?=$row['title']?></h5>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                <a href="#" class="btn btn-primary">Go somewhere</a>
            </div>
        </div>

    <?php } ?>
</div>
