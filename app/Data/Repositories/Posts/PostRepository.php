<?php


namespace App\Data\Repositories\Posts;

use App\Data\Models\Posts\Post;
use App\Data\Repositories\BaseRepository;

/**
 * Class PostRepository
 *
 * @package App\Data\Repositories\Posts
 */
class PostRepository extends BaseRepository
{
    /**
     * @var Post
     */
    protected $post_model;

    /**
     * PostRepository constructor.
     * @param Post $post
     */
    public function __construct(
        Post $post
    ){
        $this->post_model = $post;
    }

    /**
     * @param array $data
     * @return PostRepository
     */
    public function create(array $data)
    {
        $post = $this->post_model->init($data);

        //region Data validation
        if (!$post->validate($data)) {
            $errors = $post->getErrors();
            return $this->setResponse([
                'status' => 500,
                'message' => $errors[0],
                'meta' => [
                    'errors' => $errors,
                ],
            ]);
        }
        //endregion Data validation

        //region Data insertion
        if (!$post->save()) {
            $errors = $post->getErrors();
            return $this->setResponse([
                'status' => 500,
                'message' => 'An error has occurred while saving the post',
                'meta' => [
                    'errors' => $errors,
                ],
            ]);
        }
        //endregion Data insertion

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully created post.',
            'data' => [
                'post' => $post,
            ],
        ]);
    }

    /**
     * @param string $id
     * @return PostRepository
     */
    public function delete($id)
    {
        //region Data validation
        if (trim($id) == '') {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The post id is not set or invalid.',
            ]);
        }
        //endregion Data validation

        //region Existence check
        $post =  $this->post_model->findByUUID($id);

        if (!$post) {
            return $this->setResponse([
                'status' => 404,
                'message' => 'The post does not exist.',
            ]);
        }
        //endregion Existence check

        //region Data deletion
        if (!$post->delete()) {
            return $this->setResponse([
                'status' => 500,
                'message' => 'An error has occurred while deleting the post.',
            ]);
        }
        //endregion Data deletion

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully deleted post.',
        ]);
    }

    /**
     * @param $id
     * @return PostRepository
     */
    public function fetch($id)
    {
        //region Existence check
        $post =  $this->post_model->findByUUID($id);

        if (!$post) {
            return $this->setResponse([
                'status' => 404,
                'message' => 'The post does not exist.',
            ]);
        }
        //endregion Existence check

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully fetched post.',
            'data' => [
                'post' => $post,
            ],
        ]);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function search(array $data)
    {
        // TODO: Implement search() method.
    }

    /**
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function update($id, array $data)
    {
        // TODO: Implement update() method.
    }
}
