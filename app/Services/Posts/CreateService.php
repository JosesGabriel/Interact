<?php


namespace App\Services\Posts;

use App\Services\BaseService;
use App\Data\Repositories\Posts\PostRepository;

/**
 * Class CreateService
 *
 * @package App\Services\Posts
 */
class CreateService extends BaseService
{
    /**
     * @var PostRepository
     */
    protected $post_repo;

    /**
     * CreateService constructor.
     * @param PostRepository $postRepository
     */
    public function __construct(
        PostRepository $postRepository
    ){
        $this->post_repo = $postRepository;
    }

    /**
     * @param array $data
     * @return PostRepository|CreateService
     */
    public function handle(array $data)
    {
        //region Data validation
        $valid_statuses = array_keys(config('arbitrage.posts.model.status'));

        if (!isset($data['status']) ||
            !in_array($data['status'], $valid_statuses)) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The status is not set or invalid.',
            ]);
        }

        $valid_visibility = array_keys(config('arbitrage.posts.model.visibility'));

        if (!isset($data['visibility']) ||
            !in_array($data['visibility'], $valid_visibility)) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The visibility is not set or invalid.',
            ]);
        }
        //endregion Data validation

        $response = $this->post_repo->create($data);

        return $response;
    }
}
