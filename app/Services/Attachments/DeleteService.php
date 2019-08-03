<?php


namespace App\Services\Attachments;

use App\Data\Repositories\Attachments\AttachmentRepository;
use App\Services\BaseService;

/**
 * Class DeleteService
 *
 * @package App\Services\Attachments
 */
class DeleteService extends BaseService
{
    /**
     * @var AttachmentRepository
     */
    private $attachment_repo;

    /**
     * DeleteService constructor.
     * @param AttachmentRepository $attachmentRepository
     */
    public function __construct(
        AttachmentRepository $attachmentRepository
    ){
        $this->attachment_repo = $attachmentRepository;
    }

    /**
     * @param array $data
     * @return AttachmentRepository|DeleteService
     */
    public function handle(array $data)
    {
        //region Data validation
        if (!isset($data['id']) ||
            !is_numeric($data['id'])) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The attachment id is not set or invalid.',
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

        $response = $this->attachment_repo->delete($data['id']);

        return $response;
    }
}
