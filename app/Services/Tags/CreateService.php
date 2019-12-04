<?php

namespace App\Services\Tags;

use App\Data\Repositories\Tags\TagRepository;
use App\Services\BaseService;

/**
 * Class CreateService
 *
 * @package App\Services\Tags
 */
class CreateService extends BaseService
{
    /**
     * @var TagRepository
     */
    private $tag_repo;

    /**
     * CreateService constructor.
     * @param TagRepository $tagRepository
     */
    public function __construct(
        TagRepository $tagRepository
    ){
        $this->tag_repo = $tagRepository;
    }

    /**
     * @param array $data
     * @return CreateService|TagRepository
     */
    public function handle(array $data): object
    {
        //region Data validation
        if (!isset($data['taggable_id']) ||
            !is_numeric($data['taggable_id'])) {
            return $this->setResponse([
                'status' => 400,
                'message' => 'The taggable id is not set or invalid.',
            ]);
        }

        if (!isset($data['taggable_type']) ||
            !is_numeric($data['taggable_type'])) {
            return $this->setResponse([
                'status' => 400,
                'message' => 'The taggable type is not set or invalid.',
            ]);
        }

        if (!isset($data['tag_id']) ||
            !is_numeric($data['tag_id'])) {
            return $this->setResponse([
                'status' => 400,
                'message' => 'The tag id is not set or invalid.',
            ]);
        }

        if (!isset($data['tag_type']) ||
            !is_numeric($data['tag_type'])) {
            return $this->setResponse([
                'status' => 400,
                'message' => 'The tag type is not set or invalid.',
            ]);
        }
        //endregion Data validation

        $response = $this->tag_repo->create($data);

        return $response;
    }
}
