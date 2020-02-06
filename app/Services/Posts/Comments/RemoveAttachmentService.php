<?php


namespace App\Services\Posts\Comments;

use App\Data\Repositories\Posts\Comments\CommentRepository;
use App\Services\Attachments\DeleteService as DeleteAttachmentService;
use App\Services\BaseService;

/**
 * Class RemoveAttachmentService
 *
 * @package App\Services\Posts\Comments
 */
class RemoveAttachmentService extends BaseService
{
    /**
     * RemoveAttachmentService constructor.
     * @param CommentRepository $commentRepository
     * @param DeleteAttachmentService $deleteService
     */
    public function __construct(
        CommentRepository $commentRepository,
        DeleteAttachmentService $deleteService
    ){
        $this->comment_repo = $commentRepository;
        $this->delete_attachment = $deleteService;
    }

    /**
     * @param array $data
     * @return \App\Data\Repositories\Attachments\AttachmentRepository|CommentRepository|DeleteAttachmentService|RemoveAttachmentService
     */
    public function handle(array $data) : object
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
        $data['id'] = $data['attachment_id'];
        unset($data['attachment_id']);

        $response = $this->delete_attachment->handle($data);

        return $response;
    }
}
