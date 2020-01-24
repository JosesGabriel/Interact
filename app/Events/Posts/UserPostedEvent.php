<?php

namespace App\Events\Posts;

use App\Data\Models\Posts\Post;
use Illuminate\Queue\SerializesModels;

/**
 * Class UserPostedEvent
 *
 * @package App\Events\Posts
 */
class UserPostedEvent
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
        $this->post = $post;
    }
}
