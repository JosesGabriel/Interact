<?php


namespace App\Services\Posts\Comments;

use App\Data\Repositories\Posts\Comments\CommentRepository;
use App\Services\BaseService;

/**
 * Class DeleteService
 *
 * @package App\Services\Posts\Comments
 */
class DeleteService extends BaseService
{
    /**
     * DeleteService constructor.
     * @param CommentRepository $commentRepository
     */
    public function __construct(
        CommentRepository $commentRepository
    ){
        $this->comment_repo = $commentRepository;
    }

    /**
     * @param array $data
     * @return CommentRepository|DeleteService|array
     */
    public function handle(array $data)
    {
        //region Data validation
        if (!isset($data['id'])) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The comment id is not set or invalid.',
            ]);
        }

        if (!isset($data['user_id'])) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The user id is not set or invalid.',
            ]);
        }
        //endregion Data validation

        //region Existence check
        $comment = $this->comment_repo->fetch($data['id']);
        if ($comment->isError()) {
            return $comment;
        }
        //endregion Existence check

        $comment = $comment->getDataByKey('comment');

        // if this comment does not belong to the user
        if ($data['user_id'] !== $comment->user_id) {
            return $this->setResponse([
                'status' => 403,
                'message' => 'The user is unable to delete this post.',
            ]);
        }
        //endregion Existence check

        $response = $this->comment_repo->delete($data['id']);
        return $response;
    }
}
