<?php

namespace App\Events\Followers;

use App\Data\Models\Followers\Follower;
use App\Events\BaseEvent;
use Illuminate\Queue\SerializesModels;

/**
 * Class UserFollowedEvent
 *
 * @package App\Events\Followers
 */
class UserFollowedEvent extends BaseEvent
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
        parent::__construct();
        $this->follower = $follower;
    }
}
