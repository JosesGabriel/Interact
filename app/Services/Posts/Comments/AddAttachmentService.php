<?php


namespace App\Services\Posts\Comments;

use App\Data\Repositories\Posts\Comments\CommentRepository;
use App\Services\Attachments\CreateService as CreateAttachmentService;
use App\Services\BaseService;

/**
 * Class AddAttachmentService
 *
 * @package App\Services\Posts\Comments
 */
class AddAttachmentService extends BaseService
{
    /**
     * @var CreateAttachmentService
     */
    private $create_attachment;

    /**
     * @var CommentRepository
     */
    private $comment_repo;

    /**
     * AddAttachmentService constructor.
     * @param CommentRepository $commentRepository
     * @param CreateAttachmentService $createService
     */
    public function __construct(
        CommentRepository $commentRepository,
        CreateAttachmentService $createService
    ){
        $this->create_attachment = $createService;
        $this->comment_repo = $commentRepository;
    }

    /**
     * @param array $data
     * @return \App\Data\Repositories\Attachments\AttachmentRepository|CommentRepository|CreateAttachmentService|AddAttachmentService
     */
    public function handle(array $data)
    {
        //region Data validation
        if (!isset($data['id']) ||
            !is_numeric($data['id'])) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The comment id is not set or invalid.',
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
        $comment = $this->comment_repo->fetch($data['id']);

        if ($comment->isError()) {
            return $comment;
        }
        //endregion Existence check

        $comment = $comment->getDataByKey('comment');

        //region Authorization check
        if ($data['user_id'] != $comment->user_id) {
            return $this->setResponse([
                'status' => 403,
                'message' => 'The user is not allowed of this action.',
            ]);
        }
        //endregion Authorization check

        unset($data['id']);

        $response = $this->create_attachment->handle($data);

        return $response;
    }
}
