<?php

$base = 'App\Data\Models';

return [
    'attachments' => [
        'attachment' => "$base\Attachments\Attachment",
    ],
    'posts' => [
        'post' => "$base\Post",

        'comments' => [
            'comment' => "$base\Posts\Comments\Comment",
        ],
    ],
];
