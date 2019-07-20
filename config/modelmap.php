<?php

$base = 'App\Data\Models';

return [
    'posts' => [
        'post' => "$base\Post",

        'comments' => [
            'comment' => "$base\Posts\Comments\Comment",
        ],
    ],
];
