<?php


namespace App\Services\Attachments;

use App\Data\Repositories\Attachments\AttachmentRepository;
use App\Services\BaseService;

/**
 * Class CreateManyService
 *
 * @package App\Services\Attachments
 */
class CreateManyService extends BaseService
{
    /**
     * @var AttachmentRepository
     */
    private $attachment_repo;

    /**
     * CreateManyService constructor.
     * @param AttachmentRepository $attachmentRepository
     */
    public function __construct(
        AttachmentRepository $attachmentRepository
    ){
        $this->attachment_repo = $attachmentRepository;
    }

    /**
     * @param array $data
     * @return AttachmentRepository|CreateManyService
     */
    public function handle(array $data) : object
    {
        $model_config = config('arbitrage.attachments.model');

        //region Data validation
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
            !is_array($data['url'])) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The url is not set or invalid.',
            ]);
        }
        //endregion Data validation

        //region Data insertion
        $attachments = [];
        $failed = [];

        foreach ($data['url'] as $url) {

            $attachment = [
                'attachable_id' => $data['attachable_id'],
                'attachable_type' => $data['attachable_type'],
                'user_id' => $data['user_id'],
                'url' => $url,
            ];

            $response = $this->attachment_repo->create($attachment);

            if ($response->isError()) {
                $failed[] = [
                    'message' => $response->getMessage(),
                    'url' => $url,
                ];
                continue;
            }

            $attachments[] = $response->getDataByKey('attachment');
        }
        //endregion Data insertion

        if (!empty($failed)) {
            return $this->setResponse([
                'status' => 500,
                'message' => 'An error has occurred while saving attachments.',
                'data' => [
                    'attachments' => $attachments,
                    'failed' => $failed,
                ],
            ]);
        }

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully created comments.',
            'data' => [
                'attachments' => $attachments,
            ],
        ]);
    }
}
