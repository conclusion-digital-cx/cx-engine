<?php
$items = $db->select("news", [
    "[>]media" => ["media" => "id"],
], [
	"news.id",
	"news.title",
	"media" => [
        "media.url"
    ],
], ["LIMIT" => 50]);
// d($items);
// d($db->last());
?>
<div class="container">
    <div class="row">
        <?php foreach ($items as $value) { ?>
            <div class="col-sm my-4">
                <div class="card" style="width: 18rem;">
                <?php if($value['media']) { ?>
                    <img class="card-img-top" src="<?= $value['media']['url']; ?>" alt="Card image cap">
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