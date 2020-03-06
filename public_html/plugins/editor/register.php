<?php
$blocks['editor'] = getBlocksFromPath(__DIR__."/blocks");

return isset($_GET['editor']) ? 
[
    'head' => [
        function ($page) {
            return <<<EOT
            <script>
                window.contenttools = {
                    pageId: {$page->id}
                }
            </script>
        EOT;
        },
        $blocks['editor']['head'],
    ],
    'afterbody' => [
        $blocks['editor']['toolbar']
    ],
    'main' => [
        $blocks['editor']['main']
    ],
    'footer' => [
        $blocks['editor']['footer']
    ]
] : [
    'head' => [],
    'afterbody' => [
        $blocks['editor']['toolbar']   // TODO only admins
    ],
    'main' => [],
    'footer' => []
];
