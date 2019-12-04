<?php

namespace App\Services\Tags;

use App\Data\Repositories\Tags\TagRepository;
use App\Services\BaseService;

/**
 * Class DeleteService
 *
 * @package App\Services\Tags
 */
class DeleteService extends BaseService
{
    /**
     * @var TagRepository
     */
    private $tag_repo;

    /**
     * DeleteService constructor.
     * @param TagRepository $tagRepository
     */
    public function __construct(
        TagRepository $tagRepository
    ){
        $this->tag_repo = $tagRepository;
    }

    /**
     * @param array $data
     * @return DeleteService|TagRepository
     */
    public function handle(array $data): object
    {
        //region Data validation
        if (!isset($data['id'])) {
            return $this->setResponse([
                'status' => 400,
                'message' => 'The tag id is not set or invalid.',
            ]);
        }
        //endregion Data validation

        $response = $this->tag_repo->delete($data['id']);

        return $response;
    }
}
