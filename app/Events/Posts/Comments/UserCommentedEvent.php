<?php

namespace App\Events\Posts\Comments;

use App\Data\Models\Posts\Comments\Comment;
use App\Events\BaseEvent;
use Illuminate\Queue\SerializesModels;

/**
 * Class UserCommentedEvent
 *
 * @package App\Events\Posts\Comments
 */
class UserCommentedEvent extends BaseEvent
{
    use SerializesModels;

    /**
     * @var Comment
     */
    public $comment;

    /**
     * UserCommentedEvent constructor.
     * @param Comment $comment
     */
    public function __construct(Comment $comment)
    {
        parent::__construct();
        $this->comment = $comment;
    }
}
