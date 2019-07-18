<?php


namespace App\Services\Posts;

use App\Data\Repositories\Posts\PostRepository;
use App\Services\BaseService;

/**
 * Class DeleteService
 *
 * @package App\Services\Posts
 */
class DeleteService extends BaseService
{
    /**
     * @var PostRepository
     */
    protected $post_repo;

    /**
     * DeleteService constructor.
     * @param PostRepository $postRepository
     */
    public function __construct(
        PostRepository $postRepository
    ){
        $this->post_repo = $postRepository;
    }

    /**
     * @param array $data
     * @return PostRepository|DeleteService
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

        if (!isset($data['user_id']) ||
            trim($data['user_id']) == '') {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The user id is not set or invalid.',
            ]);
        }
        //endregion Data validation

        //region Existence check
        $post = $this->post_repo->fetch($data['id']);

        if ($post->isError()) {
            return $post;
        }

        $post = $post->getDataByKey('post');

        // if this post does not belong to the user
        if ($data['user_id'] !== $post->user_id) {
            return $this->setResponse([
                'status' => 403,
                'message' => 'The user is unable to delete this post.',
            ]);
        }
        //endregion Existence check

        $response = $this->post_repo->delete($data['id']);
        return $response;
    }
}
