<?php


namespace App\Services\Posts\Comments;

use App\Data\Repositories\Posts\Comments\CommentRepository;
use App\Services\BaseService;

/**
 * Class UpdateService
 *
 * @package App\Services\Posts\Comments
 */
class UpdateService extends BaseService
{
    /**
     * @var CommentRepository
     */
    private $comment_repo;

    /**
     * UpdateService constructor.
     * @param CommentRepository $commentRepository
     */
    public function __construct(
        CommentRepository $commentRepository
    ){
        $this->comment_repo = $commentRepository;
    }

    /**
     * @param array $data
     * @return UpdateService|mixed
     */
    public function handle(array $data) : object
    {
        //region Data validation
        if (!isset($data['id']) ||
            !is_numeric($data['id'])) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The comment id is not set or invalid.',
            ]);
        }
        //endregion Data validation

        $response = $this->comment_repo->update($data['id'], $data);

        return $response;
    }
}
