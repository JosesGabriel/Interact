<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Services\TrendingService;
use App\Services\Users\FollowingService;
use App\Services\Users\FollowerService;
use Illuminate\Http\Request;

class TrendingController extends BaseController
{
    public function trending(
        Request $request,
        TrendingService $trendingService
    )
    {
        $data = $request->all();
        $response = $trendingService->handle($data);

        return $this->absorb($response)->respond();
    }

    public function users(
        Request $request,
        TrendingService $trendingService
    )
    {
        $data = $request->all();
        $data['type'] = "users";
        $response = $trendingService->handle($data);

        return $this->absorb($response)->respond();
    }

    public function following(
        Request $request,
        FollowingService $followingservice
    )
    {
        $data = $request->all();
        $response = $followingservice->handle($data);

        return $this->absorb($response)->respond();
    }

    public function followers(
        Request $request,
        FollowerService $followerservice
    )
    {
        $data = $request->all();
        $response = $followerservice->handle($data);

        return $this->absorb($response)->respond();
    }


}
