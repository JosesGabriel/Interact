<?php

namespace App\Listeners\Sentiments\Comments;

use App\Events\Sentiments\UserSentimentedEvent;
use App\Jobs\SendWebNotification;
use App\Listeners\HasUserWebNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class AuthorSentimentNotification
 *
 * @package App\Listeners\Sentiments\Comments
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

        if ($sentiment->sentimentable_type == config('arbitrage.sentiments.model.sentimentable_type.comment.value')) {
            $this->setWebNotification([
                'message' => "{$user['name']} has registered a {$sentiment->type}ish sentiment on your comment.",
                'data' => [
                    'comment' => $event->sentimentable_data,
                    'post' => [
                        'id' => $event->sentimentable->post_id,
                    ],
                    'sentiment' => [
                        'type' => $sentiment->type,
                    ],
                    'user' => $user,
                ],
            ], 'social.comment.sentiment', $event->sentimentable->user_id)->sendWebNotification();
        }
    }
}
