<?php

namespace App\Listeners\Posts\Comments;

use App\Events\Posts\Comments\UserCommentedEvent;
use App\Listeners\HasUserWebNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class MentionNotification
 *
 * @package App\Listeners\Posts\Comments
 */
class MentionNotification implements ShouldQueue
{
    use HasUserWebNotification;
    use InteractsWithQueue;

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
        $user = $event->request_user;

        if ($tagged_users) {
            $data = [
                'message' => "{$user['username']} has mentioned you.",
                'data' => [
                    'comment' => [
                        'id' => $comment->id,
                    ],
                    'post' => [
                        'id' => $comment->post->id,
                    ],
                    'user' => $user,
                ],
            ];
            foreach ($tagged_users as $tagged_user) {
                if ($tagged_user->tag_id == $user['uuid']) {
                    continue;
                }
                $this->setWebNotification($data, 'social.comment.tag:user', $tagged_user->tag_id)->sendWebNotification();
            }
        }
    }
}
