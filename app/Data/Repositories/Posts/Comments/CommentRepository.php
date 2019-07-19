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
