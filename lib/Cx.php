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

    function __construct($config)
    {
        $this->router = new Router();   // Fork of AltoRouter

        // Decompose config
        $this->theme = $config->theme;
        $this->debug = $config->debug;
        
        // ======================
        // Search routes
        // $dir    = '/views';
        // $files = glob(  'views/**/*.*' );
        // print_r($files);
        
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

    // Match request. Returns Page() object
    function getPage()
    {
        $obj = $this->match();
        return new Page($obj);
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
        // global $blocks, 
        $regions = $this->regions;
        $page = $this->page;   // Context for render

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

    function render()
    {
        $page = $this->match();
        $regions = $this->regions;

        // =========
        // Theme helper functions
        // =========
        function region($name)
        {
            // return $this->region($name);
            return 'cool';
        }
        function render($file, $ctx = "")
        {
            ob_start();
            include $file;
            return ob_get_clean();
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

        if ($page) {
            $regions['main'][] = renderTemplate($page->body, $this->blocks);
        } else {
            $url = strtok($_SERVER["REQUEST_URI"], '?');
            $regions['main'][] = "<h1>Page doesn't exist.</h1>";
        }

        $theme = $this->theme;
        // include __DIR__."/../themes/$theme/index.php";
        $html = render(__DIR__ . "/../themes/$theme/index.php");
        return $html;
    }

    function __toString()
    {
        return $this->render();
    }


    function debug($what, $title = "")
    {
        if ($this->debug) {
        // if (isset($_GET['debug']) || $this->debug) {
            debugToConsole($what, $title);
        }
    }
}
