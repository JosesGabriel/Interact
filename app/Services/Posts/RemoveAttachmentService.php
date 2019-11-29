<?php


namespace App\Services\Posts;

use App\Data\Repositories\Posts\PostRepository;
use App\Services\Attachments\DeleteService as DeleteAttachmentService;
use App\Services\BaseService;

/**
 * Class RemoveAttachmentService
 *
 * @package App\Services\Posts
 */
class RemoveAttachmentService extends BaseService
{
    /**
     * @var DeleteAttachmentService
     */
    private $delete_attachment;

    /**
     * @var PostRepository
     */
    private $post_repo;

    /**
     * RemoveAttachmentService constructor.
     * @param DeleteAttachmentService $deleteService
     * @param PostRepository $postRepository
     */
    public function __construct(
        DeleteAttachmentService $deleteService,
        PostRepository $postRepository
    ){
        $this->delete_attachment = $deleteService;
        $this->post_repo = $postRepository;
    }

    /**
     * @param array $data
     * @return \App\Data\Repositories\Attachments\AttachmentRepository|PostRepository|DeleteAttachmentService|RemoveAttachmentService
     */
    public function handle(array $data) : object
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
        //endregion Existence check

        $post = $post->getDataByKey('post');

        //region Authorization check
        if ($data['user_id'] != $post->user_id) {
            return $this->setResponse([
                'status' => 403,
                'message' => 'The user is not allowed of the action.',
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
