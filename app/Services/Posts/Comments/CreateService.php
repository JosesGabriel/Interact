<?php


namespace App\Services\Posts\Comments;

use App\Data\Repositories\Posts\Comments\CommentRepository;
use App\Events\Posts\Comments\UserCommentedEvent;
use App\Services\BaseService;

/**
 * Class CreateService
 *
 * @package App\Services\Posts\Comments
 */
class CreateService extends BaseService
{
    /**
     * @var CommentRepository
     */
    private $comment_repo;

    /**
     * CreateService constructor.
     * @param CommentRepository $commentRepository
     */
    public function __construct(
        CommentRepository $commentRepository
    ){
        $this->comment_repo = $commentRepository;
    }

    /**
     * @param array $data
     * @return CommentRepository|CreateService
     */
    public function handle(array $data) : object
    {
        //region Data validation
        if (!isset($data['post_id'])) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The post id is not set or invalid.',
            ]);
        }

        if (!isset($data['user_id'])) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The user id is not set or invalid.',
            ]);
        }

        if (!isset($data['parent_id'])) {
            $data['parent_id'] = 0;
        }
        //endregion Data validation

        $response = $this->comment_repo->create($data);

        if ($response->isError()) {
            return $response;
        }

        $comment = $response->getDataByKey('comment');

        //region Create Tags
        if (isset($data['tags']) &&
            is_array($data['tags'])) {

            $taggable_type = config('arbitrage.tags.model.taggable_type.comment.value');

            $tags = collect($data['tags'])->map(function ($tag) use ($comment, $taggable_type) {
                $tag['taggable_id'] = $comment->id;
                $tag['taggable_type'] = $taggable_type;
                return $tag;
            });

            $comment->tags()->createMany($tags);

            $response->addData('tags', $tags);
        }
        //endregion Create Tags

        event(new UserCommentedEvent($comment));

        return $response;
    }
}
