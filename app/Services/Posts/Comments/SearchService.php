<?php


namespace App\Services\Posts\Comments;

use App\Data\Repositories\Posts\Comments\CommentRepository;
use App\Services\BaseService;

/**
 * Class SearchService
 *
 * @package App\Services\Posts\Comments
 */
class SearchService extends BaseService
{
    /**
     * @var CommentRepository
     */
    private $comment_repo;

    /**
     * SearchService constructor.
     * @param CommentRepository $commentRepository
     */
    public function __construct(
        CommentRepository $commentRepository
    ){
        $this->comment_repo = $commentRepository;
    }

    /**
     * @param array $data
     * @return CommentRepository
     */
    public function handle(array $data)
    {
        $query = [];

        //region Build query
        if (isset($data['post_id'])) {
            $query['where'][] = ['post_id', '=', $data['post_id']];
        }

        if (isset($data['user_id'])) {
            $query['where'][] = ['user_id', '=', $data['user_id']];
        }

        if (isset($data['parent_id'])) {
            $query['where'][] = ['parent_id', '=', $data['parent_id']];
        }

        $query = array_merge($data, $query);
        //endregion Build query

        $response = $this->comment_repo->search($query);

        return $response;
    }
}
