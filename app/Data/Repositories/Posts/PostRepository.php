<?php


namespace App\Data\Repositories\Posts;

use App\Data\Models\Posts\Post;
use App\Data\QueryFilters\Post\OrderBy;
use App\Data\QueryFilters\Post\Page;
use App\Data\QueryFilters\Post\Status;
use App\Data\QueryFilters\Post\UserId;
use App\Data\QueryFilters\Post\Visibility;
use App\Data\QueryFilters\Post\WallId;
use App\Data\Repositories\BaseRepository;
use Illuminate\Pipeline\Pipeline;

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
     * @param integer|string $id
     * @return PostRepository
     * @throws \Exception
     */
    public function delete($id)
    {
        //region Data validation
        if (!isset($id) ||
            !is_numeric($id)) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The post id is not set or invalid.',
            ]);
        }
        //endregion Data validation

        //region Data deletion
        $post = $this->post_model->find($id);
        if (!$post->delete()) {
            $errors = $post->getErrors();
            return $this->setResponse([
                'status' => 500,
                'message' => 'An error has occurred while deleting the post.',
                'meta' => [
                    'errors' => $errors,
                ],
            ]);
        }
        //endregion Data deletion

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully deleted post.',
        ]);
    }

    /**
     * @param integer|string $id
     * @return PostRepository
     */
    public function fetch($id)
    {
        //region Existence check
        $post =  $this->post_model
            ->with([
                'attachments',
                'bears',
                'bulls',
                'comments' => function ($query) {
                    $query->with(['comments'])->withCount(['comments']);
                },
            ])
            ->withCount([
                'attachments',
                'bears',
                'bulls',
                'comments',
            ])
            ->find($id);

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
            'meta' => [
                'count' => [
                    'attachments' => $post->attachments()->count(),
                    'comments' => $post->comments()->count(),
                ],
            ],
        ]);
    }

    /**
     * @param array $data
     * @return PostRepository
     */
    public function search(array $data)
    {
        $query = Post::query()
            ->with([
                'attachments',
                'bears',
                'bulls',
                'comments' => function ($query) {
                    $query->take(config('arbitrage.posts.config.relations.comments.max'));
                },
            ])
            ->withCount([
                'attachments',
                'bears',
                'bulls',
                'comments',
            ]);

        //region Data filter
        $posts = app(Pipeline::class)
            ->send($query)
            ->through([
                OrderBy::class,
                Page::class,
                Status::class,
                UserId::class,
                Visibility::class,
                WallId::class,
            ])
            ->thenReturn()
            ->get();
        //endregion Data filter

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully searched posts.',
            'data' => [
                'posts' => $posts,
            ],
            'meta' => [
                'count' => [
                    'posts' => $posts->count(),
                ],
            ],
        ]);
    }

    /**
     * @param $id
     * @param array $data
     * @return PostRepository
     */
    public function update($id, array $data)
    {
        $post_id = null;
        $user_id = null;

        //region Data validation
        if (!isset($id) ||
            !is_numeric($id)) {
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

        if (isset($data['id'])) {
            unset($data['id']);
        }
        //endregion Data validation

        $user_id = $data['user_id'];
        unset($data['user_id']);

        //region Existence check
        $post = $this->fetch($id);

        if ($post->isError()) {
            return $post;
        }
        //endregion Existence check

        $post = $post->getDataByKey('post');

        //region Authorization check
        // if this post does not belong to the user
        if ($user_id !== $post->user_id) {
            return $this->setResponse([
                'status' => 403,
                'message' => 'The user is unable to update this post.',
            ]);
        }
        //endregion Authorization check

        //region Data update
        if (!$post->save($data)) {
            $errors = $post->getErrors();
            return $this->setResponse([
                'status' => 500,
                'message' => 'An error has occurred while updating the post.',
                'meta' => [
                    'errors' => $errors,
                ],
            ]);
        }
        //endregion Data update

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully updated the post.',
            'data' => [
                'post' => $post,
            ],
        ]);
    }
}
