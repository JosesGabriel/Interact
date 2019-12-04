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
     * @return mixed
     */
    public function create(array $data)
    {
        // TODO: Implement create() method.
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param $id
     * @return mixed
     */
    public function fetch($id)
    {
        // TODO: Implement fetch() method.
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
     * @return mixed
     */
    public function update($id, array $data)
    {
        // TODO: Implement update() method.
    }
}
