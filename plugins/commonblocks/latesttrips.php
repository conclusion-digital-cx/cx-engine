<?php
require_once __DIR__."/../lib/Collection.php";
$collection = new Collection("trips");
?>

    <div class="row">
        <?php foreach($collection->getAll() as $row) { ?>

            <div class="col card" style="width: 18rem;">
                <img src="<?=$row['image']?>" class="card-img-top" alt="<?=$row['title']?>">
                <div class="card-body">
                    <h5 class="card-title"><?=$row['title']?></h5>
                    <!-- <p class="card-text"><?=$row['title']?></p> -->
                    <a href="/trips/<?=$row['id']?>" class="btn btn-primary">Go somewhere</a>
                </div>
            </div>

        <?php } ?>
    </div>
