<?php

namespace App\Data\Repositories\Tags;

use App\Data\Models\Tags\Tag;
use App\Data\Repositories\BaseRepository;

/**
 * Class TagRepository
 *
 * @package App\Data\Repositories\Tags
 */
class TagRepository extends BaseRepository
{
    /**
     * @var Tag
     */
    private $tag_model;

    /**
     * TagRepository constructor.
     * @param Tag $tag
     */
    public function __construct(
        Tag $tag
    ){
        $this->tag_model = $tag;
    }

    /**
     * @param array $data
     * @return TagRepository
     */
    public function create(array $data)
    {
        $tag = $this->tag_model->init($data);

        //region Data validation
        if (!$tag->validate($data)) {
            $errors = $tag->getErrors();
            return $this->setResponse([
                'status' => 500,
                'message' => $errors[0],
                'meta' => [
                    'errors' => $errors,
                ],
            ]);
        }
        //endregion Data validation

        //region Data insertion
        if (!$tag->save()) {
            $errors = $tag->getErrors();
            return $this->setResponse([
                'status' => 500,
                'message' => 'An error has occurred while saving the tag.',
                'meta' => [
                    'errors' => $errors,
                ],
            ]);
        }
        //endregion Data insertion

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully created tag.',
            'data' => [
                'tag' => $tag,
            ],
        ]);
    }

    /**
     * @param $id
     * @return TagRepository
     */
    public function delete($id)
    {
        //region Data validation
        if (!isset($id) ||
            !is_numeric($id)) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The tag id is not set or invalid.',
            ]);
        }
        //endregion Data validation

        //region Data deletion
        $tag = $this->tag_model->find($id);

        if (!$tag->delete()) {
            $errors = $tag->getErrors();
            return $this->setResponse([
                'status' => 500,
                'message' => 'An error has occurred while deleting the tag.',
                'meta' => [
                    'errors' => $errors,
                ],
            ]);
        }
        //endregion Data deletion

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully deleted the tag.',
        ]);
    }

    /**
     * @param $id
     * @return TagRepository
     */
    public function fetch($id)
    {
        //region Existence check
        $tag = $this->tag_model->find($id);

        if (!$tag) {
            return $this->setResponse([
                'status' => 404,
                'message' => 'The tag does not exist.',
            ]);
        }
        //endregion Existence check

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully fetched tag.',
            'data' => [
                'tag' => $tag,
            ],
        ]);
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
     * @return TagRepository
     */
    public function update($id, array $data)
    {
        //region Existence check
        $tag = $this->fetch($id);

        if ($tag->isError()) {
            return $tag;
        }
        //endregion Existence check

        $tag = $tag->getDataByKey('tag');

        //region Data update
        if (!$tag->save($data)) {
            $errors = $tag->getErrors();
            return $this->setResponse([
                'status' => 500,
                'message' => 'An error has occurred while updating the tag.',
                'meta' => [
                    'errors' => $errors,
                ],
            ]);
        }
        //endregion Data update

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully updated the tag.',
            'data' => [
                'tag' => $tag,
            ],
        ]);
    }
}
