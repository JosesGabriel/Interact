<?php

namespace App\Services\Tags;

use App\Data\Repositories\Tags\TagRepository;
use App\Services\BaseService;

/**
 * Class FetchService
 *
 * @package App\Services\Tags
 */
class FetchService extends BaseService
{
    /**
     * @var TagRepository
     */
    private $tag_repo;

    /**
     * FetchService constructor.
     * @param TagRepository $tagRepository
     */
    public function __construct(
        TagRepository $tagRepository
    ){
        $this->tag_repo = $tagRepository;
    }

    /**
     * @param array $data
     * @return FetchService|TagRepository
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

        $response = $this->tag_repo->fetch($data['id']);

        return $response;
    }
}
