<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Services\TrendingService;
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
}
