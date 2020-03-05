<?php

return [
    'get' => function ($row) {
        return [
            'password' => password_hash($row['password'],PASSWORD_BCRYPT)
        ];
    },
    'save' => function ($row) {
        return [
            'password' => password_hash($row['password'],PASSWORD_BCRYPT)
        ];
    },
];
