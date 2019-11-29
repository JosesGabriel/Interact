<?php


namespace App\Services\Posts;

use App\Data\Repositories\Posts\PostRepository;
use App\Services\BaseService;

/**
 * Class SearchService
 *
 * @package App\Services\Posts
 */
class SearchService extends BaseService
{
    /**
     * @var PostRepository
     */
    private $post_repo;

    /**
     * SearchService constructor.
     * @param PostRepository $postRepository
     */
    public function __construct(
        PostRepository $postRepository
    ){
        $this->post_repo = $postRepository;
    }

    /**
     * @param array $data
     * @return PostRepository
     */
    public function handle(array $data) : object
    {
        $response = $this->post_repo->search($data);

        return $response;
    }
}
