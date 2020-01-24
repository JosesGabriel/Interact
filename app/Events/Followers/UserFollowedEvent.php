<?php

namespace App\Events\Followers;

use App\Data\Models\Followers\Follower;
use Illuminate\Queue\SerializesModels;

/**
 * Class UserFollowedEvent
 *
 * @package App\Events\Followers
 */
class UserFollowedEvent
{
    use SerializesModels;

    /**
     * @var Follower
     */
    public $follower;

    /**
     * Create a new event instance.
     *
     * @param Follower $follower
     */
    public function __construct(Follower $follower)
    {
        $this->follower = $follower;
    }
}
