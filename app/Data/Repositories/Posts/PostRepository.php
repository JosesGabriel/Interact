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
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param $id
     * @return mixed
     */
    public function fetch($id)
    {
        // TODO: Implement fetch() method.
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
