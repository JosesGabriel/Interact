<?php


namespace App\Services\Attachments;

use App\Data\Repositories\Attachments\AttachmentRepository;
use App\Services\BaseService;

/**
 * Class CreateService
 *
 * @package App\Services\Attachments
 */
class CreateService extends BaseService
{
    /**
     * @var AttachmentRepository
     */
    private $attachment_repo;

    /**
     * CreateService constructor.
     * @param AttachmentRepository $attachmentRepository
     */
    public function __construct(
        AttachmentRepository $attachmentRepository
    ){
        $this->attachment_repo = $attachmentRepository;
    }

    /**
     * @param array $data
     * @return AttachmentRepository|CreateService
     */
    public function handle(array $data)
    {
        $model_config = config('arbitrage.attachments.model');
        //region Data vaidation
        if (!isset($data['attachable_id']) ||
            !is_numeric($data['attachable_id'])) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The target id is not set or invalid.',
            ]);
        }

        $valid_models = array_keys($model_config['attachable_type']);

        if (!isset($data['attachable_type']) ||
            !in_array($data['attachable_type'], $valid_models)) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The target type is not set or invalid.',
            ]);
        }

        if (!isset($data['user_id']) ||
            trim($data['user_id']) == '') {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The user id is not set or invalid.',
            ]);
        }

        if (!isset($data['url']) ||
            trim($data['url']) == '') {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The url is not set or invalid.',
            ]);
        }
        //endregion Data validation

        $response = $this->attachment_repo->create($data);

        return $response;
    }
}
