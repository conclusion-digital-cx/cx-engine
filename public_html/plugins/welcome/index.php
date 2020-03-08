<?php 

// Root
$router->get("/",function ($req, $res, $next) {
    $adminLink = $req->user ? 'Visit the <a href="/admin/">admin</a> environment.' : '';
    echo <<<HTML
        <div class="container">
            <h1>Welcome</h1>
            <p>CxEngine is a high performance (headless) CMS</p>
            $adminLink
        </div>
    HTML;
});
