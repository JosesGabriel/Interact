<?php

namespace App\Http\Controllers\Followers;

use App\Http\Controllers\BaseController;
use App\Services\Followers\FollowService;
use App\Services\Followers\UnfollowService;
use Illuminate\Http\Request;

/**
 * Class FollowerController
 *
 * @package App\Http\Controllers\Followers
 */
class FollowerController extends BaseController
{
    /**
     * @param Request $request
     * @param FollowService $followService
     * @param $follow_id
     * @return \Illuminate\Http\Response
     */
    public function follow(
        Request $request,
        FollowService $followService,
        $follow_id
    ){
        $data = $request->all();

        $data['follow_id'] = $follow_id;

        $response = $followService->handle($data);

        return $this->absorb($response)->respond();
    }

    /**
     * @param Request $request
     * @param UnfollowService $unfollowService
     * @param $follow_id
     * @return \Illuminate\Http\Response
     */
    public function unfollow(
        Request $request,
        UnfollowService $unfollowService,
        $follow_id
    ){
        $data = $request->all();

        $data['follow_id'] = $follow_id;

        $response = $unfollowService->handle($data);

        return $this->absorb($response)->respond();
    }
}
