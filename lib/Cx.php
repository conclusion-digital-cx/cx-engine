<?php

include "AltoRouter.php";
include "CxDb.php";
include "Page.php";
include "utils.php";
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
            $requestUrl = strtok($_SERVER["REQUEST_URI"], '?'); // str_replace("?editor", "", $requestUrl);
            // debug($requestUrl);

            $url = SQLite3::escapeString($requestUrl);
            $sql = "SELECT * from pages WHERE url='$url' LIMIT 1";
            $ret = $db->query($sql);
            $page = $ret->fetchArray(SQLITE3_ASSOC);
            // debug($page);

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
