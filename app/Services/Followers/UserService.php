<?php

namespace App\Services\Followers;

use App\Data\Repositories\Followers\FollowerRepository;
use App\Services\BaseService;

/**
 * Class UserService
 *
 * @package App\Services\Followers
 */
class UserService extends BaseService
{
    /**
     * @var FollowerRepository
     */
    private $follower_repo;


    /**
     * UserService constructor.
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
                'message' => 'The user id is not set or invalid.',
            ]);
        }
        //endregion Data validation

        if (!isset($data['user_id']) ||
            $data['follow_id'] == $data['user_id']) {
            $data['user_id'] = null;
        }

        $response = $this->follower_repo->fetchUserProfile($data['follow_id'], $data['user_id']);

        return $response;
    }
}
