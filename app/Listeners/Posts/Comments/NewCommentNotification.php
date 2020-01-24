<?php

namespace App\Listeners\Posts\Comments;

use App\Events\Posts\Comments\UserCommentedEvent;
use App\Jobs\SendWebNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class NewCommentNotification
 *
 * @package App\Listeners\Posts\Comments
 */
class NewCommentNotification implements ShouldQueue
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
     * @param  UserCommentedEvent  $event
     * @return void
     */
    public function handle(UserCommentedEvent $event)
    {
        $comment = $event->comment;

        $this->sendWebNotification::dispatch([
            'message' => 'There is a new comment.',
            'data' => [
                'post' => [
                    'id' => $comment->post_id,
                ],
            ],
        ], 'social.post');
    }
}
