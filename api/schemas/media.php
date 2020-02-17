<?php

return [
    'get' => function ($row) {
        // Computed fields
        $computed = [
            'imageUrl' => "http://localhost:8666$row[image]"
        ];
        return array_merge($row, $computed);
    }
];
