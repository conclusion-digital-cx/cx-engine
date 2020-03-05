<?php

return [
    'get' => function ($row) {
        return [
            'attributes' => json_decode($row['attributes'])
        ];
    },
    'save' => function ($row) {
        return [
            'attributes' => json_encode($row['attributes'])
        ];
    },
];
