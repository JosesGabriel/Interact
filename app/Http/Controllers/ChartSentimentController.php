<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Services\Sentiments\ChartSentimentService;
use Illuminate\Http\Request;

class ChartSentimentController extends BaseController
{
    public function sentimentalize(
        Request $request,
        ChartSentimentService $chartSentiment
    ){
        $data = $request->all();
        $data['action'] = "add";
        $response = $chartSentiment->handle($data);

        return $this->absorb($response)->respond();
    }

    public function get_sentiment(
        Request $request,
        ChartSentimentService $chartSentiment
    )
    {
        $data = $request->all();
        $data['action'] = "get";
        $response = $chartSentiment->handle($data);

        return $this->absorb($response)->respond();

    }

    
}
