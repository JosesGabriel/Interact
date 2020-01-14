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
        $data['stock_id'] = $data['query']['stock_id'];
        $data['market_code'] = $data['query']['market_code'];
        $data['type'] = $data['query']['sentiment'];
        $data['sentiment'] = $data['query']['sentiment'];
        $data['user_id'] = $data['query']['user_id'];
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
        // $data['stock_id'] = $data['query']['stock_id'];
        // $data['market_code'] = $data['query']['market_code'];
        // $data['user_id'] = $data['query']['user_id'];
        $response = $chartSentiment->handle($data);

        return $this->absorb($response)->respond();

    }

    
}
