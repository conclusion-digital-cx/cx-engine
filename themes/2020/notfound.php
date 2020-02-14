<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <div id='app'>
        <?php block('nav'); ?>
        <h1>Page not found</h1>
        <pre><?= isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/' ?></pre>
    </div>

</body>

</html>