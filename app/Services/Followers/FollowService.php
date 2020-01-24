<?php

namespace App\Services\Followers;

use App\Data\Repositories\Followers\FollowerRepository;
use App\Events\Followers\UserFollowedEvent;
use App\Services\BaseService;

/**
 * Class FollowService
 *
 * @package App\Services\Followers
 */
class FollowService extends BaseService
{
    /**
     * @var FollowerRepository
     */
    private $follower_repo;

    /**
     * FollowService constructor.
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

        $data['follower_id'] = $data['user_id'];
        $data['user_id'] = $data['follow_id'];

        $response = $this->follower_repo->create($data);

        if ($response->isError()) {
            return $response;
        }

        $follower = $response->getDataByKey('follow');

        event(new UserFollowedEvent($follower));

        return $response;
    }
}
