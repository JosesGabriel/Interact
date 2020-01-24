<?php

namespace App\Listeners\Sentiments\Posts;

use App\Events\Sentiments\UserSentimentedEvent;
use App\Jobs\SendWebNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class AuthorSentimentNotification
 *
 * @package App\Listeners\Sentiments\Posts
 */
class AuthorSentimentNotification implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * @var SendWebNotification
     */
    private $sendWebNotification;

    /**
     * Create the event listener.
     *
     * @param SendWebNotification $sendWebNotification
     */
    public function __construct(SendWebNotification $sendWebNotification)
    {
        $this->sendWebNotification = $sendWebNotification;
    }

    /**
     * Handle the event.
     *
     * @param  UserSentimentedEvent  $event
     * @return void
     */
    public function handle(UserSentimentedEvent $event)
    {
        $sentiment = $event->sentiment;

        if ($sentiment->sentimentable_type == config('arbitrage.sentiments.model.sentimentable_type.post.value')) {
            $this->sendWebNotification::dispatch([
                'message' => "A user has registered a {$sentiment->type}ish sentiment on your post.",
                'data' => [
                    'post' => $event->sentimentable_data,
                    'sentiment' => [
                        'type' => $sentiment->type,
                    ],
                    'user' => $event->user_data,
                ],
            ], 'social.post.sentiment', $event->sentimentable->user_id);
        }
    }
}
