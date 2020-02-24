<?php

require_once "Router.php";
require_once "CxDb.php";
require_once "Page.php";
// include "utils.php";


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
    public $theme = "2020";
    public $blocks = [];
    public $regions = [
        'title' => [],
        'head' => [],
        'afterbody' => [],
        'content' => [],
        'menu' => [],
        'main' => [],
        'footer' => []
    ];
    public $router;
    public $page;

    function __construct($config)
    {
        $this->router = new Router();   // Fork of AltoRouter

        // Decompose config
        $this->theme = $config->theme;
        $this->debug = $config->debug;
        $this->root = $config->root;

        // Database
        $dbpath = $config->db['database_file'];
        $db = new CxDb($dbpath);

        if (!$db) {
            echo $db->lastErrorMsg();
        } else {
            // echo "Opened database successfully\n";
        }

        // Save
        $this->db = $db;
    }

    // Set callback
    function notfound($cb)
    {
        $this->notfound = $cb;
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
        $newBlocks = $this->getBlocksFromPath($path);
        $this->blocks = array_merge($this->blocks, $newBlocks);
    }

    // Match request. Returns Page() object
    function getPage()
    {
        $obj = $this->match();
        return new Page($obj);
    }

    function run()
    {
        // match current request url
        $matches = $this->router->matches();
        // debugToConsole($matches);
        // debugToConsole($this->router);

        foreach ($matches as $match) {
            // Call closure
            $request = (object) $match['params'];
            $resp = $match['target']($request);

            // Stop loop on false returns
            if (!$resp) break;
        }
    }

    // TODO move to Medoo
    // Match request. Returns object from Database
    function match()
    {
        // match current request url
        $matches = $this->router->matches();
        // debugToConsole($matches);
        // debugToConsole($this->router);

        foreach ($matches as $match) {
            // Call closure
            $request = (object) $match['params'];
            $resp = $match['target']($request);

            // Stop loop on false returns
            if (!$resp) break;
        }

        // // call closure, match database
        // if (is_array($match) && is_callable($match['target'])) {
        //     call_user_func_array($match['target'], $match['params']);
        // } else {
        //     $requestUrl = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';

        //     // =============
        //     // Get Page from DB
        //     // =============
        //     $db = $this->db;
        //     // $requestUrl = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';

        //     // Remove editor from url
        //     $requestUrl = strtok($_SERVER["REQUEST_URI"], '?'); // str_replace("?editor", "", $requestUrl);
        //     // debug($requestUrl);

        //     $url = SQLite3::escapeString($requestUrl);
        //     $sql = "SELECT * from pages WHERE url='$url' LIMIT 1";
        //     $ret = $db->query($sql);
        //     $page = $ret->fetchArray(SQLITE3_ASSOC);
        //     // debug($page);

        //     if (!$page) {
        //         // header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
        //         // exit("Page not found: $requestUrl");
        //         return false;
        //     }

        //     // Convert to object
        //     $page = (object) $page;
        //     $page->blocks = json_decode($page->blocks);
        //     return $page;
        // }
    }

    function region($name)
    {
        // Context for render
        $regions = $this->regions;
        $page = $this->page;   
        $blocks = $this->blocks;

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

    function renderFile($file)
    {
        ob_start();
        include $file;
        return ob_get_clean();
    }

    // function render()
    // {
    //     $page = $this->match();
    //     $regions = $this->regions;

    //     if ($page) {
    //         $regions['main'][] = renderTemplate($page->body, $this->blocks);
    //     } else {
    //         $url = strtok($_SERVER["REQUEST_URI"], '?');
    //         $regions['main'][] = "<h1>Page doesn't exist.</h1>";
    //     }


    //     // include __DIR__."/../themes/$theme/index.php";
    //     $html = $this->renderFile($this->root . "/themes/{$this->theme}/index.php");
    //     // $html = render(__DIR__ . "/../themes/$theme/index.php");
    //     return $html;
    // }

    function __toString()
    {
        return $this->renderFile($this->root . "/themes/{$this->theme}/index.php");
    }

    function debug($what, $title = "")
    {
        if ($this->debug) {
            // if (isset($_GET['debug']) || $this->debug) {
            debugToConsole($what, $title);
        }
    }
}
