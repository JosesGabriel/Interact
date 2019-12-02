<?php

namespace App\Services\Followers;

use App\Data\Repositories\Followers\FollowerRepository;
use App\Services\BaseService;

/**
 * Class UnfollowService
 *
 * @package App\Services\Followers
 */
class UnfollowService extends BaseService
{
    /**
     * @var FollowerRepository
     */
    private $follower_repo;

    /**
     * UnfollowService constructor.
     * @param FollowerRepository $followerRepository
     */
    public function __construct(
        FollowerRepository $followerRepository
    ){
        $this->follower_repo = $followerRepository;
    }

    /**
     * @param array $data
     * @return FollowerRepository
     */
    public function handle(array $data): object
    {
        //region Data validation
        if (!isset($data['follow_id'])) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The follow id is not set or invalid.',
            ]);
        }

        if (!isset($data['user_id'])) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The user id is not set or invalid.',
            ]);
        }
        //endregion Data validation

        $response = $this->follower_repo->unfollow($data['follow_id'], $data['user_id']);

        return $response;
    }
}
