<?php

namespace App\Data\Repositories\Followers;

use App\Data\Models\Followers\Follower;
use App\Data\Repositories\BaseRepository;

/**
 * Class FollowerRepository
 *
 * @package App\Data\Repositories\Followers
 */
class FollowerRepository extends BaseRepository
{
    /**
     * @var Follower
     */
    private $follower_model;

    /**
     * FollowerRepository constructor.
     * @param Follower $follower
     */
    public function __construct(
        Follower $follower
    ){
        $this->follower_model = $follower;
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
