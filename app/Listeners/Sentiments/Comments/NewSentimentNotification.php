<?php

namespace App\Listeners\Sentiments\Comments;

use App\Events\Sentiments\UserSentimentedEvent;
use App\Jobs\SendWebNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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

        if ($sentiment->sentimentable_type == config('arbitrage.sentiments.model.sentimentable_type.comment.value')) {
            $this->sendWebNotification::dispatch([
                'message' => 'A new sentiment.',
                'data' => [
                    'comment' => $event->sentimentable_data,
                    'post' => [
                        'id' => $event->sentimentable->post_id,
                    ],
                    'sentiment' => [
                        'type' => $sentiment->type,
                    ],
                ],
            ], 'social.comment.sentiment');
        }
    }
}
