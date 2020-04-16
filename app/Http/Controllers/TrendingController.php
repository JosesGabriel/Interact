<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Services\TrendingService;
use App\Services\Trending\TrendingStockService;
use App\Services\Trending\SuggestedUserService;
use App\Services\Users\FollowingService;
use App\Services\Users\FollowerService;
use Illuminate\Http\Request;

class TrendingController extends BaseController
{
    /**
     * Get Trending Stocks
     */
    public function trending(
        Request $request,
        TrendingStockService $trendingService
    )
    {
        $data = $request->all();
        $response = $trendingService->handle($data);

        return $this->absorb($response)->respond();
    }

    /**
     * Get Suggested Users 
     */
    public function users(
        Request $request,
        SuggestedUserService $trendingService
    )
    {
        $data = $request->all();
        $data['type'] = "users";
        $response = $trendingService->handle($data);

        return $this->absorb($response)->respond();
    }

    /**
     * Following Functions
     */
    public function following(
        Request $request,
        FollowingService $followingservice
    )
    {
        $data = $request->all();
        $response = $followingservice->handle($data);

        return $this->absorb($response)->respond();
    }

    /**
     * Followers Functions
     */
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
