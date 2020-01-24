<?php

namespace App\Listeners\Sentiments\Posts;

use App\Events\Sentiments\UserSentimentedEvent;
use App\Jobs\SendWebNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class NewSentimentNotification
 *
 * @package App\Listeners\Sentiments\Posts
 */
class NewSentimentNotification implements ShouldQueue
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
                'message' => 'A new sentiment.',
                'data' => [
                    'post' => $event->sentimentable_data,
                    'sentiment' => [
                        'type' => $sentiment->type,
                    ],
                ],
            ], 'social.post.sentiment');
        }
    }
}
