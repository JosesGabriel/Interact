<?php


namespace App\Data\Repositories\Attachments;

use App\Data\Models\Attachments\Attachment;
use App\Data\Repositories\BaseRepository;

/**
 * Class AttachmentRepository
 *
 * @package App\Data\Repositories\Attachments
 */
class AttachmentRepository extends BaseRepository
{
    /**
     * @var Attachment
     */
    private $attachment_model;

    /**
     * AttachmentRepository constructor.
     * @param Attachment $attachment
     */
    public function __construct(
        Attachment $attachment
    ){
        $this->attachment_model = $attachment;
    }

    /**
     * @param array $data
     * @return AttachmentRepository
     */
    public function create(array $data)
    {
        $attachment = $this->attachment_model->init($data);

        //region Data validation
        if (!$attachment->validate($data)) {
            $errors = $attachment->getErrors();
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
        if (!$attachment->save()) {
            $errors = $attachment->getErrors();
            return $this->setResponse([
                'status' => 500,
                'message' => 'An error has occurred while saving the attachment.',
                'meta' => [
                    'errors' => $errors,
                ],
            ]);
        }
        //endregion Data insertion

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully created an attachment.',
            'data' => [
                'attachment' => $attachment,
            ],
        ]);
    }

    /**
     * @param $id
     * @return AttachmentRepository
     */
    public function delete($id)
    {
        //region Data validation
        if (!isset($id) ||
            !is_numeric($id)) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The attachment id is not set or invalid.',
            ]);
        }
        //endregion Data validation

        //region Existence check
        $attachment = $this->fetch($id);

        if ($attachment->isError()) {
            return $attachment;
        }
        //endregion Existence check

        //region Data deletion
        $attachment = $attachment->getDataByKey('attachment');

        if (!$attachment->delete()) {
            $errors = $attachment->getErrors();
            return $this->setResponse([
                'status' => 500,
                'message' => 'An error has occurred while deleting the attachment.',
                'meta' => [
                    'errors' => $errors,
                ],
            ]);
        }
        //endregion Data deletion

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully deleted the attachment.',
        ]);
    }

    /**
     * @param $id
     * @return AttachmentRepository
     */
    public function fetch($id)
    {
        //region Data validation
        if (!isset($id) ||
            !is_numeric($id)) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The attachment id is not set or invalid.',
            ]);
        }
        //endregion Data validation

        //region Existence check
        $attachment = $this->attachment_model->find($id);

        if (!$attachment) {
            return $this->setResponse([
                'status' => 404,
                'message' => 'The attachment does not exist.',
            ]);
        }
        //endregion Existence check

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully fetch attachment.',
            'data' => [
                'attachment' => $attachment,
            ],
        ]);
    }

    /**
     * @param array $data
     * @return AttachmentRepository
     */
    public function search(array $data)
    {
        $model = Attachment::query();

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
            'message' => 'Successfully searched attachments.',
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
