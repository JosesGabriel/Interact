<?php


namespace App\Services\Posts\Comments;

use App\Data\Repositories\Posts\Comments\CommentRepository;
use App\Services\BaseService;

/**
 * Class CreateService
 *
 * @package App\Services\Posts\Comments
 */
class CreateService extends BaseService
{
    /**
     * @var CommentRepository
     */
    private $comment_repo;

    /**
     * CreateService constructor.
     * @param CommentRepository $commentRepository
     */
    public function __construct(
        CommentRepository $commentRepository
    ){
        $this->comment_repo = $commentRepository;
    }

    /**
     * @param array $data
     * @return CommentRepository|CreateService
     */
    public function handle(array $data)
    {
        //region Data validation
        if (!isset($data['post_id'])) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The post id is not set or invalid.',
            ]);
        }

        if (!isset($data['user_id'])) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The user id is not set or invalid.',
            ]);
        }

        if (!isset($data['parent_id'])) {
            $data['parent_id'] = 0;
        }
        //endregion Data validation

        $response = $this->comment_repo->create($data);

        return $response;
    }
}
