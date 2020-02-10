<?php

/*
Generate a nice html page
*/

class Page
{
    public $id = 0;
    public $title = "No title";

    // Regions
    public $head = "";
    public $body;
    public $content = "";
    public $footer = "";

    public function __construct($page)
    {
        $this->id = isset($page->id) ? $page->id : 0;
        $this->title = isset($page->title) ? $page->title : 'no title';
        $this->body = isset($page->body) ? $page->body : "";
        $this->blocks = isset($page->blocks) ? $page->blocks : [];
        // print_r($page);
    }

    public function add($plugin) {
        // echo "Adding plugin $plugin";

        $plugindir = "plugins";
        
        ob_start();
        include "$plugindir/$plugin/head.html";
        $this->head .= ob_get_contents();
        ob_end_clean();

        ob_start();
        include "$plugindir/$plugin/index.html";
        $this->content .= ob_get_contents();
        ob_end_clean();

        ob_start();
        include "$plugindir/$plugin/footer.html";
        $this->footer .= ob_get_contents();
        ob_end_clean();
    }


    // OPTIONAL when page is being echo
    public function __toString()
    {
        // return $this->body;
        return '<!DOCTYPE html>
<html>

<head>
    <title>'.$this->title.'</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    '.$this->head.'
</head>

<body>
<div id="app" class="v-application--wrap">
    '.$this->body.'
</div>
'.$this->footer.'

</body>
</html>';
    }
}
