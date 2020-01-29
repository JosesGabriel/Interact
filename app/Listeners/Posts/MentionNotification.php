<?php

namespace App\Listeners\Posts;

use App\Events\Posts\UserPostedEvent;
use App\Jobs\SendWebNotification;
use App\Listeners\HasUserWebNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class MentionNotification
 *
 * @package App\Listeners\Posts
 */
class MentionNotification implements ShouldQueue
{
    use HasUserWebNotification;
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  UserPostedEvent  $event
     * @return void
     */
    public function handle(UserPostedEvent $event)
    {
        $post = $event->post;
        $tagged_users = $post->taggedUsers;
        $user = $event->request_user;

        if ($tagged_users) {
            $data = [
                'message' => "{$user['username']} has mentioned you.",
                'data' => [
                    'post' => [
                        'id' => $post->id,
                    ],
                    'user' => $user,
                ],
            ];
            foreach ($tagged_users as $tagged_user) {
                $this->setWebNotification($data, 'social.post.tag:user', $tagged_user->tag_id)->sendWebNotification();
            }
        }
    }

    /**
     * Handle a job failure.
     *
     * @param UserPostedEvent $event
     * @param \Exception $exception
     * @return void
     */
    public function failed(UserPostedEvent $event, $exception)
    {

    }
}
