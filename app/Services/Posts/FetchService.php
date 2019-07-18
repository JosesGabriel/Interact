<?php


namespace App\Services\Posts;

use App\Data\Repositories\Posts\PostRepository;
use App\Services\BaseService;

/**
 * Class FetchService
 *
 * @package App\Services\Posts
 */
class FetchService extends BaseService
{
    /**
     * @var PostRepository
     */
    private $post_repo;

    /**
     * FetchService constructor.
     * @param PostRepository $postRepository
     */
    public function __construct(
        PostRepository $postRepository
    ){
        $this->post_repo = $postRepository;
    }

    /**
     * @param array $data
     * @return PostRepository|FetchService
     */
    public function handle(array $data)
    {
        //region Data validation
        if (!isset($data['id']) ||
            !is_numeric($data['id'])) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The post id is not set or invalid.',
            ]);
        }
        //endregion Data validation

        $response = $this->post_repo->fetch($data['id']);

        return $response;
    }
}
