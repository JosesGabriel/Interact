<?php


namespace App\Services\Posts;

use App\Data\Repositories\Posts\PostRepository;
use App\Services\BaseService;

/**
 * Class UpdateService
 *
 * @package App\Services\Posts
 */
class UpdateService extends BaseService
{
    /**
     * @var PostRepository
     */
    private $post_repo;

    /**
     * UpdateService constructor.
     * @param PostRepository $postRepository
     */
    public function __construct(
        PostRepository $postRepository
    ){
        $this->post_repo = $postRepository;
    }

    /**
     * @param array $data
     * @return PostRepository|UpdateService
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

        $response = $this->post_repo->update($data['id'], $data);

        return $response;
    }
}
