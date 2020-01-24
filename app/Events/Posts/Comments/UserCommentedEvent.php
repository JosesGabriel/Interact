<?php

namespace App\Events\Posts\Comments;

use App\Data\Models\Posts\Comments\Comment;
use Illuminate\Queue\SerializesModels;

/**
 * Class UserCommentedEvent
 *
 * @package App\Events\Posts\Comments
 */
class UserCommentedEvent
{
    use SerializesModels;

    /**
     * @var Comment
     */
    private $comment;

    /**
     * UserCommentedEvent constructor.
     * @param Comment $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }
}
