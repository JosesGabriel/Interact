<?php


namespace App\Data\Repositories\Posts\Comments;

use App\Data\Models\Posts\Comments\Comment;
use App\Data\Repositories\BaseRepository;

/**
 * Class CommentRepository
 *
 * @package App\Data\Repositories\Posts\Comments
 */
class CommentRepository extends BaseRepository
{
    /**
     * @var Comment
     */
    protected $comment_model;

    /**
     * CommentRepository constructor.
     * @param Comment $comment
     */
    public function __construct(
        Comment $comment
    ){
        $this->comment_model = $comment;
    }

    public function all(array $data)
    {

    }

    /**
     * @param array $data
     * @return CommentRepository
     */
    public function create(array $data)
    {
        $comment = $this->comment_model->init($data);

        //region Data validation
        if (!$comment->validate($data)) {
            $errors = $comment->getErrors();
            return $this->setResponse([
                'status' => 417,
                'message' => $errors[0],
                'meta' => [
                    'errors' => $errors,
                ],
            ]);
        }
        //endregion Data validation

        //region Data insertion
        if (!$comment->save()) {
            $errors = $comment->getErrors();
            return $this->setResponse([
                'status' => 500,
                'message' => 'An error has occurred while saving the comment.',
                'meta' => [
                    'errors' => $errors,
                ],
            ]);
        }
        //endregion Data insertion

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully created comment.',
            'data' => [
                'comment' => $comment,
            ],
        ]);
    }

    /**
     * @param $id
     * @return CommentRepository
     */
    public function delete($id)
    {
        //region Data validation
        if (!isset($id) ||
            !is_numeric($id)) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The comment id is not set or invalid.',
            ]);
        }
        //endregion Data validation

        //region Data deletion
        $comment = $this->comment_model->find($id);
        if (!$comment->delete()) {
            $errors = $comment->getErrors();
            return $this->setResponse([
                'status' => 500,
                'message' => 'An error has occurred while deleting the comment.',
                'meta' => [
                    'errors' => $errors,
                ],
            ]);
        }
        //endregion Data deletion

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully deleted comment.',
        ]);
    }

    /**
     * @param $id
     * @return CommentRepository
     */
    public function fetch($id)
    {
        //region Existence check
        $comment = $this->comment_model->find($id);

        if (!$comment) {
            return $this->setResponse([
                'status' => 404,
                'message' => 'The comment does not exist.',
            ]);
        }
        //endregion Existence check

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully fetched the comment.',
            'data' => [
                'comment' => $comment,
            ],
        ]);
    }

    /**
     * @param array $data
     * @return CommentRepository
     */
    public function search(array $data)
    {
        $model = Comment::query();

        //region Build query
        if (isset($data['where'])) {
            $model->where($data['where']);
        }

        if (isset($data['with'])) {
            $model->with($data['with']);
        }
        //endregion Build query

        $result = $model->get();

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully searched comments.',
            'data' => [
                'comments' => $result,
            ],
        ]);
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