<?php

include "AltoRouter.php";
include "CxDb.php";
include "Page.php";

// ============
// Global helpers
// ============

/**
 * Simple helper to debug to the console
 *
 * @param $data object, array, string $data
 * @param $context string  Optional a description.
 *
 * @return string
 */
function debugToConsole($data, $context = 'Debug in Console') {

    // Buffering to solve problems frameworks, like header() in this and not a solid return.
    ob_start();

    $output  = 'console.info(\'' . $context . ':\');';
    $output .= 'console.log(' . json_encode($data) . ');';
    $output  = sprintf('<script>%s</script>', $output);

    echo $output;
}


// Add plugin to page
function add($name, $what = "") {
    
}

// Plugin resolver
function block($name) {

    // Include : index.php
    $file = "blocks/$name/index.php";
    if(file_exists($file)) {
        include $file;
    }
    
    // Include once : index.css
    $file = "blocks/$name/style.css";
    if(file_exists($file)) {
        echo "<style>";
        require_once $file;
        echo "</style>";
    }

    // Resolve template areas : footer.html / head.html / body.html
    $file = "blocks/$name/footer.html";
    if(file_exists($file)) {
        echo "<style>";
        require_once $file;
        echo "</style>";
    }
}

class Cx {
    public $notfound;
    public $debug = false;

    function __construct($dbpath = "test.db") {
        $router = new AltoRouter();

        // ======================
        // Search routes
        // $dir    = '/views';
        // $files = glob(  'views/**/*.*' );
        // print_r($files);

        // Database
        $db = new CxDb($dbpath);

        if(!$db) {
            echo $db->lastErrorMsg();
        } else {
            // echo "Opened database successfully\n";
        }

        // Save
        $this->db = $db;
        $this->router = $router;
    }

    // Set callback
    function notfound($cb) {
        $this->notfound = $cb;
    }

    // Create page
    function match() {
        // match current request url
        $match = $this->router->match();

        // call closure or throw 404 status
        if( is_array($match) && is_callable( $match['target'] ) ) {
            call_user_func_array( $match['target'], $match['params'] ); 
        } else {
            $requestUrl = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
            
            // =============
            // Get Page from DB
            // =============
            $db = $this->db;
            $requestUrl = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';

            // Remove editor from url
            $requestUrl = str_replace("?editor","",$requestUrl );

            $sql ="SELECT * from pages WHERE url='".SQLite3::escapeString($requestUrl)."' LIMIT 1";
            $ret = $db->query($sql);
            $page = $ret->fetchArray(SQLITE3_ASSOC);

            // Debug
            if($this->debug) debugToConsole($page);

            $renderBlock = function($blocks) {
                $body = "";
                foreach($blocks as $block) {
                    $name = $block->name;
    
                    ob_start();
                    include "blocks/$name.php";
                    $html = ob_get_contents();
                    ob_end_clean();
    
                    $body .= $html;
                }
                return $body;
            };

            // Server side render blocks
            $blocks = json_decode($page['pageblocks']);
            if($this->debug) debugToConsole($blocks);

            // Set body
            // $page['body'] = "Test";
            $page['body'] = $blocks ? $renderBlock($blocks) : $page['body'];
            // $page->body = $body;

            // =============
            // Page not found
            // =============
            if(!$page) {
                // if($this->notfound) {
                //     $closure = $this->notfound;
                //     $page = $closure($requestUrl);
                // } else {
                    // no route was matched
                    header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
                    $page = "<h1>Page not found</h1><pre><?= $requestUrl ?></pre>";
                // }
            } else {
                return new Page($page);
            }
        }
    }
 }
