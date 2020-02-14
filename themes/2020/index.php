<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$page->title?></title>
    <?=$page->head?>
    <script>
        window.settings = {
            pageId:<?=$page->id?>
        }
    </script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <?php // block('nav'); ?>
       <!-- Editor TODO admin only-->
    <div style="z-index:10;position:absolute;right:0;padding:5px; background: #ddd; display:flex;">
        <?php if(isset($_GET['editor'])) { ?>
            <a style="padding:0 5px 0 5px" href="<?=str_replace("?editor","",$_SERVER['REQUEST_URI'])?>">Disable editor</a>
        <?php } else { ?>
            <a style="padding:0 5px 0 5px" href="?editor">Enable editor</a>
        <?php } ?>
        <a style="padding:0 5px 0 5px" href="/admin">Backend</a>
        <!-- <a style="padding:0 5px 0 5px" href="/control/">Backend (new)</a> -->
        <a style="padding:0 5px 0 5px" target="_blank" href="/api/pages/<?=$page->id?>">page#<?=$page->id?></a>
        <!-- <div style="padding-left:10px;">page#<?=$page->id?></div> -->
    </div>

    <div id='app' class="v-application--wrap">
        <div class="container">
            <?=$page->body?>
        </div>
    </div>

    <?=$page->footer?>
</body>
</html>