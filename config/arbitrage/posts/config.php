<?php

return [
    'query' => [
        'limit' => env('QUERY_POST_LIMIT', 10),
    ],
    'relations' => [
        'comments' => [
            'max' => 3,
        ],
    ],
];
