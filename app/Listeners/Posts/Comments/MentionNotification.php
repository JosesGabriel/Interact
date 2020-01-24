<?php

namespace App\Listeners\Posts\Comments;

use App\Events\Posts\Comments\UserCommentedEvent;
use App\Jobs\SendWebNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class MentionNotification
 *
 * @package App\Listeners\Posts\Comments
 */
class MentionNotification implements ShouldQueue
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
        $tagged_users = $comment->taggedUsers;

        if ($tagged_users) {
            foreach ($tagged_users as $user) {
                $this->sendWebNotification::dispatch([
                    'message' => 'A user has mentioned you.',
                    'data' => [
                        'comment' => [
                            'id' => $comment->id,
                        ],
                        'post' => [
                            'id' => $comment->post->id,
                        ],
                    ],
                ], 'social.comment.tag:user', $user->tag_id);
            }
        }
    }
}
