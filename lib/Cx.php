<?php

include "AltoRouter.php";
include "CxDb.php";
include "Page.php";

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
            // 'zone' => 'afterbody'
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

function zone($name)
{
    global $blocks, $zones;
    global $page;   // Context for render

    $blocksInZone = $zones[$name];
    debug("Render zone: $name");
    foreach ($blocksInZone as $mixed) {
        // $block = $blocks[$blockName];
        $block = $mixed;

        if(isset($block->file)) {
            include $block->file;
        } else {
            echo $block;
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

class Cx
{
    public $notfound;
    public $debug = false;

    function __construct($config)
    {
        $router = new AltoRouter();

        $dbpath = $config->db['database_file'];

        // ======================
        // Search routes
        // $dir    = '/views';
        // $files = glob(  'views/**/*.*' );
        // print_r($files);

        // Database
        $db = new CxDb($dbpath);

        if (!$db) {
            echo $db->lastErrorMsg();
        } else {
            // echo "Opened database successfully\n";
        }

        // Save
        $this->db = $db;
        $this->router = $router;
    }

    // Set callback
    function notfound($cb)
    {
        $this->notfound = $cb;
    }

    // Match request. Returns Page() object
    function getPage()
    {
        $obj = $this->match();
        return new Page($obj);
    }

    // Match request. Returns object from Database
    function match()
    {
        // match current request url
        $match = $this->router->match();

        // call closure or throw 404 status
        if (is_array($match) && is_callable($match['target'])) {
            call_user_func_array($match['target'], $match['params']);
        } else {
            $requestUrl = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';

            // =============
            // Get Page from DB
            // =============
            $db = $this->db;
            // $requestUrl = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';

            // Remove editor from url
            $requestUrl = strtok($_SERVER["REQUEST_URI"],'?'); // str_replace("?editor", "", $requestUrl);
            debug($requestUrl);

            $url = SQLite3::escapeString($requestUrl);
            $sql = "SELECT * from pages WHERE url='$url' LIMIT 1";
            $ret = $db->query($sql);
            $page = $ret->fetchArray(SQLITE3_ASSOC);
            debug($page);

            if (!$page) {
                // header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
                // exit("Page not found: $requestUrl");
                return false;
            }

            // Convert to object
            $page = (object) $page;
            $page->blocks = json_decode($page->blocks);
            return $page;
        }
    }



    function render($blocks)
    {
        $renderPhpBlock = function ($blocks) {
            // print_r($blocks);
            $body = "";
            foreach ($blocks as $block) {
                $name = $block->name;

                ob_start();
                include "blocks/$name.php";
                $html = ob_get_contents();
                ob_end_clean();

                $body .= $html;
            }
            return $body;
        };

        $renderJsBlock = function ($blocks) {
            // print_r($blocks);
            $body = "";
            foreach ($blocks as $block) {
                $name = $block->name;
                $body .= $block->render;
            }
            return $body;
        };

        // TODO render PHP blocks also ?

        return $renderJsBlock($blocks);
    }
}
