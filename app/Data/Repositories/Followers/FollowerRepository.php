<?php

namespace App\Data\Repositories\Followers;

use App\Data\Models\Followers\Follower;
use App\Data\Repositories\BaseRepository;
use Illuminate\Pipeline\Pipeline;

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
     * @return FollowerRepository
     */
    public function create(array $data)
    {
        $follower = $this->follower_model->init($data);

        //region Data validation
        if (!$follower->validate($data)) {
            $errors = $follower->getErrors();
            return $this->setResponse([
                'status' => 400,
                'message' => $errors[0],
                'meta' => [
                    'errors' => $errors,
                ],
            ]);
        }
        //endregion Data validation

        //region Existence check
        $follow = $this->fetchByUsers($data['user_id'], $data['follower_id']);

        if ($follow->isSuccess()) {
            return $this->setResponse([
                'status' => 400,
                'message' => 'Already following the user.',
            ]);
        }
        //endregion Existence check

        //region Data insertion
        if (!$follower->save()) {
            $errors = $follower->getErrors();
            return $this->setResponse([
                'status' => 500,
                'message' => 'An error has occurred while saving the follow',
                'meta' => [
                    'errors' => $errors,
                ],
            ]);
        }
        //endregion Data insertion

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully created follow.',
            'data' => [
                'follow' => $follower,
            ],
        ]);
    }

    /**
     * @param $id
     * @return FollowerRepository
     */
    public function delete($id)
    {
        //region Data validation
        if (!isset($id) ||
            !is_numeric($id)) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The follower id is not set or invalid.',
            ]);
        }
        //endregion Data validation

        //region Data deletion
        $follower = $this->follower_model->find($id);
        if (!$follower->delete()) {
            $errors = $follower->getErrors();
            return $this->setResponse([
                'status' => 500,
                'message' => 'An error has occurred while deleting the follow.',
                'meta' => [
                    'errors' => $errors,
                ],
            ]);
        }
        //endregion Data deletion

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully deleted follow.',
        ]);
    }

    /**
     * @param $id
     * @return FollowerRepository
     */
    public function fetch($id)
    {
        //region Existence check
        $follower =  $this->follower_model->find($id);

        if (!$follower) {
            return $this->setResponse([
                'status' => 404,
                'message' => 'The follow does not exist.',
            ]);
        }
        //endregion Existence check

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully fetched follow.',
            'data' => [
                'follow' => $follower,
            ],
        ]);
    }

    /**
     * @param $follow_id
     * @param $follower_id
     * @return FollowerRepository
     */
    public function fetchByUsers($follow_id = '', $follower_id = '')
    {
        $follow = $this->follower_model
            ->where('user_id', $follow_id)
            ->where('follower_id', $follower_id)
            ->get();

        //region Existence check
        if (!$follow->count()) {
            return $this->setResponse([
                'status' => 404,
                'message' => 'The follow does not exist.',
            ]);
        }
        //endregion Existence check

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully fetched follow.',
            'data' => [
                'follow' => $follow,
            ],
        ]);
    }

    /**
     * @param $profile_id
     * @param null $viewer_id
     * @return FollowerRepository
     */
    public function fetchUserProfile($profile_id, $viewer_id = null)
    {
        //region Existence check
        $user = $this->follower_model
            ->isFollower($profile_id, $viewer_id)
            ->isFollowing($viewer_id, $profile_id)
            ->profile($profile_id)
            ->first();

        if (!$user) {
            return $this->setResponse([
                'status' => 404,
                'message' => 'The user profile is not found.',
            ]);
        }
        //endregion Existence check

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully fetched user profile.',
            'data' => [
                'user' => $user,
            ],
        ]);
    }

    /**
     * @param array $data
     * @return FollowerRepository
     */
    public function search(array $data)
    {
        $query = Follower::query();

        //region Data filter
        $followers = app(Pipeline::class)
            ->send($query)
            ->through([

            ])
            ->thenReturn()
            ->get();
        //endregion Data filter

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully searched follows.',
            'data' => [
                'follows' => $followers,
            ],
            'meta' => [
                'count' => [
                    'follows' => $followers->count(),
                ],
            ],
        ]);
    }

    /**
     * @param string $follow_id
     * @param string $follower_id
     * @return FollowerRepository
     */
    public function unfollow($follow_id = '', $follower_id = '')
    {
        //region Existence check
        $follow = $this->fetchByUsers($follow_id, $follower_id);

        if ($follow->isError()) {
            return $follow;
        }

        $follow = $follow->getDataByKey('follow');
        //endregion Existence check

        //region Delete follow
        if (!$this->follower_model->destroy($follow->pluck('id'))) {
            return $this->setResponse([
                'status' => 500,
                'message' => 'An error has occurred while deleting the follow.',
            ]);
        }
        //endregion Delete follow

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully unfollowed the user.',
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
