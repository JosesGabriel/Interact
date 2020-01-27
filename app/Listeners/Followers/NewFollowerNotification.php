<?php

namespace App\Listeners\Followers;

use App\Events\Followers\UserFollowedEvent;
use App\Jobs\SendWebNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class NewFollowerNotification
 *
 * @package App\Listeners\Followers
 */
class NewFollowerNotification implements ShouldQueue
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
     * @param  UserFollowedEvent  $event
     * @return void
     */
    public function handle(UserFollowedEvent $event)
    {
        $follower = $event->follower;
        $user = $event->request_user;

        $this->sendWebNotification::dispatch([
            'message' => "{$user['username']} followed you.",
            'data' => [
                'follower' => [
                    'id' => $follower->follower_id,
                ],
            ],
        ], 'social.user.follow', $follower->user_id);
    }
}
