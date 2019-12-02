<?php

$base = 'App\Data\Models';

return [
    'attachments' => [
        'attachment' => "$base\Attachments\Attachment",
    ],
    'followers' => [
        'follower' => "$base\Followers\Follower",
    ],
    'posts' => [
        'post' => "$base\Posts\Post",

        'comments' => [
            'comment' => "$base\Posts\Comments\Comment",
        ],
    ],
    'sentiments' => [
        'sentiment' => "$base\Sentiments\Sentiment",
    ],
];
