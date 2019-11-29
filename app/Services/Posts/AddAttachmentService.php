<?php


namespace App\Services\Posts;

use App\Data\Repositories\Posts\PostRepository;
use App\Services\Attachments\CreateService as CreateAttachmentService;
use App\Services\BaseService;

/**
 * Class AddAttachmentService
 *
 * @package App\Services\Posts
 */
class AddAttachmentService extends BaseService
{
    /**
     * @var CreateAttachmentService
     */
    private $create_attachment;

    /**
     * @var PostRepository
     */
    private $post_repo;

    /**
     * AddAttachmentService constructor.
     * @param CreateAttachmentService $createService
     * @param PostRepository $postRepository
     */
    public function __construct(
        CreateAttachmentService $createService,
        PostRepository $postRepository
    )
    {
        $this->create_attachment = $createService;
        $this->post_repo = $postRepository;
    }

    /**
     * @param array $data
     * @return \App\Data\Repositories\Attachments\AttachmentRepository|PostRepository|CreateAttachmentService|AddAttachmentService
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

        $response = $this->create_attachment->handle($data);

        return $response;
    }
}
