<?php

namespace App\Listeners\Followers;

use App\Events\Followers\UserFollowedEvent;
use App\Listeners\HasUserWebNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class NewFollowerNotification
 *
 * @package App\Listeners\Followers
 */
class NewFollowerNotification implements ShouldQueue
{
    use HasUserWebNotification;
    use InteractsWithQueue;

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
        $data = [
            'message' => "{$user['username']} followed you.",
            'data' => [
                'follower' => [
                    'id' => $follower->follower_id,
                ],
                'user' => $user,
            ],
        ];
        $this->setWebNotification($data, 'social.user.follow', $follower->user_id )->sendWebNotification();
    }
}
