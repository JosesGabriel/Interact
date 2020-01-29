<?php

namespace App\Listeners\Sentiments\Posts;

use App\Events\Sentiments\UserSentimentedEvent;
use App\Jobs\SendWebNotification;
use App\Listeners\HasUserWebNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class AuthorSentimentNotification
 *
 * @package App\Listeners\Sentiments\Posts
 */
class AuthorSentimentNotification implements ShouldQueue
{
    use HasUserWebNotification;
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  UserSentimentedEvent  $event
     * @return void
     */
    public function handle(UserSentimentedEvent $event)
    {
        $sentiment = $event->sentiment;
        $user = $event->request_user;

        if ($sentiment->sentimentable_type == config('arbitrage.sentiments.model.sentimentable_type.post.value')) {
            $this->setWebNotification([
                'message' => "{$user['username']} has registered a {$sentiment->type}ish sentiment on your post.",
                'data' => [
                    'post' => $event->sentimentable_data,
                    'sentiment' => [
                        'type' => $sentiment->type,
                    ],
                    'user' => $user,
                ],
            ], 'social.post.sentiment', $event->sentimentable->user_id)->sendWebNotification();
        }
    }
}
