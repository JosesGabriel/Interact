<?php

namespace App\Events\Posts;

use App\Data\Models\Posts\Post;
use App\Events\BaseEvent;
use Illuminate\Queue\SerializesModels;

/**
 * Class UserPostedEvent
 *
 * @package App\Events\Posts
 */
class UserPostedEvent extends BaseEvent
{
    use SerializesModels;

    /**
     * @var Post
     */
    public $post;

    /**
     * Create a new event instance.
     *
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        parent::__construct();
        $this->post = $post;
    }
}
