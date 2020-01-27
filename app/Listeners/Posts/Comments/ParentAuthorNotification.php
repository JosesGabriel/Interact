<?php

namespace App\Listeners\Posts\Comments;

use App\Events\Posts\Comments\UserCommentedEvent;
use App\Jobs\SendWebNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class ParentAuthorNotification
 *
 * @package App\Listeners\Posts\Comments
 */
class ParentAuthorNotification implements ShouldQueue
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
        $user = $event->request_user;

        if ($comment->parent_id) {
            $event = 'comment.comment';
            $message = "{$user['username']} replied to your comment.";
            $user_id = $comment->parentComment->user_id;
        } else {
            $event = 'post.comment';
            $message = "{$user['username']} commented on your post.";
            $user_id = $comment->post->user_id;
        }

        $this->sendWebNotification::dispatch([
            'message' => $message,
            'data' => [
                'post' => [
                    'id' => $comment->post_id,
                ],
            ],
        ], "social.$event", $user_id);
    }
}
