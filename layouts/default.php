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
    <?php block('nav'); ?>
    
    <div id='app'>
        <div class="container">
            <?=$page->body?>
            <?=$page->content?>
        </div>
    </div>
    
    <?=$page->footer?>
</body>
</html>