<?php

// ============
// Global helpers
// ============

function debug($what, $title = "")
{
    global $config;
    if (isset($_GET['debug']) || $config->debug) {
        debugToConsole($what, $title);
    }
}

function getBlocksFromPath($path = "./blocks")
{
    $blocks = [];
    $files = glob("$path/*.*");

    foreach ($files as &$file) {
        // Remove ../
        // $value = substr($value, strlen('../'));
        $value = substr($file, strlen("$path/"));
        $value = substr($value, 0, -4); // Extension

        // Add to blocks
        $blocks[$value] = (object) [
            'file' => $file,
            'name' => $value
            // 'region' => 'afterbody'
        ];
    }
    return $blocks;
}

function addBlocksFromPath($path = "./blocks")
{
    global $blocks;
    $newBlocks = getBlocksFromPath($path);
    $blocks = array_merge($blocks, $newBlocks);
}

function region($name)
{
    global $blocks, $regions;
    global $page;   // Context for render

    $blocksInregion = $regions[$name];
    // debug("Render region: $name");
    foreach ($blocksInregion as $mixed) {
        // $block = $blocks[$blockName];

        $type = ($mixed instanceof Closure) ?
            'function' :
            gettype($mixed);

        // debug($mixed);
        // debug($type);
        switch ($type) {
            case 'object':
                include $mixed->file;
                break;
            case 'string':
                echo $mixed;
                break;
            case 'function':
                echo $mixed($page);
                break;
        }
    }
}

function block($blockName)
{
    global $blocks;
    global $page;   // Context for render

    $block = $blocks[$blockName];
    include $block->file;
}



/**
 * Simple helper to debug to the console
 *
 * @param $data object, array, string $data
 * @param $context string  Optional a description.
 *
 * @return string
 */
function debugToConsole($data, $context = 'Debug in Console')
{

    // Buffering to solve problems frameworks, like header() in this and not a solid return.
    ob_start();

    $output = "";
    // $output  = 'console.info(\'' . $context . ':\');';
    $output .= 'console.log(' . json_encode($data) . ');';
    $output  = sprintf('<script>%s</script>', $output);

    echo $output;
}


function renderTemplate($template, $vars)
{
    return \preg_replace_callback(
        "!{{\s*(?P<key>[a-zA-Z0-9_-]+?)\s*}}!",
        function ($match) use ($vars) {
            $key = $match["key"];
            $mixed = isset($vars[$key]) ?
                $vars[$key] :
                $match[0];

             return ($mixed instanceof Closure) ?
                $mixed() :
                $mixed;
        },
        $template
    );
}
// echo renderTemplate(
//     "<td>{{address}}</td><td>{{fixDate}}</td><td>{{measureDate}}</td><td>{{builder}}</td>",
//     array(
//         'address' => 'test two',
//     )
// );

function render($file, $ctx = "")
{
    ob_start();
    include $file;
    return ob_get_clean();
}