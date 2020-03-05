<?php

return [
    'head' => [
        function ($page) {
            return <<<HTML
            <link rel="stylesheet" type="text/css" href="/plugins/contenttools/assets/content-tools.min.css">

            <script>
                window.contenttools = {
                    pageId: {$page->id}
                }
            </script>
        HTML;
        }
    ],
    'afterbody' => [],
    'main' => [],
    'footer' => [
        function () {
            return <<<HTML
            <script src="/plugins/contenttools/assets/content-tools.min.js"></script>
            <script src="/plugins/contenttools/editor.js"></script>
            HTML;
        }
    ]
];
