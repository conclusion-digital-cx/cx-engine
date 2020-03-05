<?php
$news = $service("news")->getAll();
?>
<div class="container">
    <div class="row">
        <?php foreach ($news as $value) { ?>
            <div class="col-sm my-4">
                <div class="card" style="width: 18rem;">
                <?php if($value['media']) { ?>
                    <img class="card-img-top" src="<?= $value['media']; ?>" alt="Card image cap">
                <?php } ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= $value['title']; ?></h5>
                        <p class="card-text"><?= $value['text']; ?></p>
                        <a href="/news/<?= $value['id']?>" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>