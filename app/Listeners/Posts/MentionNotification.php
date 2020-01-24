<?php

namespace App\Listeners\Posts;

use App\Events\Posts\UserPostedEvent;
use App\Jobs\SendWebNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class MentionNotification
 *
 * @package App\Listeners\Posts
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
     * @param  UserPostedEvent  $event
     * @return void
     */
    public function handle(UserPostedEvent $event)
    {
        $post = $event->post;
        $tagged_users = $post->taggedUsers;

        if ($tagged_users) {
            foreach ($tagged_users as $user) {
                $this->sendWebNotification::dispatch([
                    'message' => 'A user has mentioned you.',
                    'data' => [
                        'post' => [
                            'id' => $post->id,
                        ],
                    ],
                ], 'social.post.tag:user', $user->tag_id);
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
