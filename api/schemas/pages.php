<?php

return [
    // 'controller' => [
    //     'get' => [ 
    //         'access' => ['public'] 
    //     ],
    //     'getOne' => [ 
    //         'access' => ['public'] 
    //     ],
    //     'post' => [ 
    //         'access' => ['public'] 
    //     ],
    //     'patch' => [ 
    //         'access' => ['public'] 
    //     ],
    //     'delete' => [ 
    //         'access' => ['public'] 
    //     ],
    // ],
    'get' => function ($row) {
        return [
            'blocks' => json_decode($row['blocks'])
        ];
    },
    'save' => function ($row) {
        return [
            'blocks' => json_encode($row['blocks'])
        ];
    },
];
