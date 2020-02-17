<?php

return [
    'get' => function ($row) {
        // Fix to respond with proper JSON
        $parseFieldsToJson = [
            // 'attributes',
            'blocks'
        ];

        // Loop doc keys
        $computed = [];
        foreach ($row as $key => $value) {
            if (in_array($key, $parseFieldsToJson)) {
                $computed[$key] = json_decode($value);
            }
        }

        return array_merge($row, $computed);
    }
];
