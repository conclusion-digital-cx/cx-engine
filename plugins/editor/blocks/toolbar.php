<!-- Editor TODO admin only-->
<div style="z-index: 100000;position:fixed;top:0;right:0;padding:5px; background: #ddd;">
    <?php if (isset($_GET['editor'])) { ?>
        <a style="padding:0 5px 0 5px" href="<?= str_replace("?editor", "", $_SERVER['REQUEST_URI']) ?>">Disable editor</a>
    <?php } else { ?>
        <a style="padding:0 5px 0 5px" href="?editor">Enable editor</a>
    <?php } ?>
    <a style="padding:0 5px 0 5px" href="/admin">Backend</a>

    <?php if ($page) { ?>
        <a style="padding:0 5px 0 5px" target="_blank" href="/admin/#/pages/edit/<?= $page->id ?>">Edit page (#<?= $page->id ?>)</a>
        <!-- <a style="padding:0 5px 0 5px" target="_blank" href="/api/pages/<?= $page->id ?>">page#<?= $page->id ?></a> -->
    <?php } else {
        $url = strtok($_SERVER["REQUEST_URI"], '?');
    ?>
        <a href='/admin/#/pages/new?url=<?= $url ?>'>Create page</a>
    <?php }  ?>

</div>