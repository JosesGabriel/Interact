<?php


namespace App\Services\Trending;

use App\Data\Providers\DataProvider;
use App\Data\Repositories\TrendingRepository;
use App\Services\BaseService;

/**
 * Class ChartSentimentService
 *
 * @package App\Services\Sentiments
 */
class TrendingStockService extends BaseService
{
    /**
     * @var SentimentRepository
     */
    private $data_provider;
    private $trending_repo;

    /**
     * ChartSentimentService constructor.
     * @param SentimentRepository $sentimentRepository
     */
    public function __construct(
        DataProvider $dataProvider,
        TrendingRepository $trendingModel
    ){
        $this->data_provider = $dataProvider;
        $this->trending_repo = $trendingModel;
    }

    /**
     * @param array $data
     * @return SentimentRepository|CreateOrUpdateService
     */
    public function handle(array $data) : object
    {

        // init vals 
        $stocks = []; // list of stocks
        
        $trending_days = 180;
        $limit = (isset($data['count']) ? $data['count'] : 5);
        // $limit = 20;

        $post_stocks = $this->trending_repo->getPostWithTags($trending_days);
        foreach ($post_stocks as $key => $value) {
            $content = $value['content'];
            $segment_content = $this->trending_repo->multiexplode(array(" ","\r\n", "."),$content);
            foreach ($segment_content as $perkey => $pervalue) {
                if (strpos($pervalue, '$') !== false) {
                    array_push($stocks, strtoupper($pervalue));
                }
            }
        }

        $comment_stocks = $this->trending_repo->getCommentWithTags($trending_days);
        foreach ($comment_stocks as $key => $value) {
            $content = $value['content'];
            $segment_content = $this->trending_repo->multiexplode(array(" ","\r\n", "."),$content);
            foreach ($segment_content as $perkey => $pervalue) {
                if (strpos($pervalue, '$') !== false) {
                    array_push($stocks, strtoupper($pervalue));
                }
            }
        }

        // filter the stocks 
        $final_stock_list = [];
        $unique_stocks = array_unique($stocks);
        foreach ($unique_stocks as $key => $value) {
            $sidebase = str_replace("$", "", $value);
            $final_stock_list[$sidebase] = 0;
        }

        foreach ($stocks as $key => $value) {
            $sidebase = str_replace("$", "", $value);
            $final_stock_list[$sidebase]++;
        }

        $response = $this->data_provider->handle([
            'uri' => "/v2/stocks/list?exchange=PSE"
        ], [])->getResponse();
        $response['data'] = array_values($response['data']);
        // dd($response['data']);

        $sentiment_info = $this->trending_repo->getChartSentiment($trending_days);

        $senti_stocks = [];
        foreach ($sentiment_info as $key => $value) {
            array_push($senti_stocks, $value['stock_id']);
        }
        $senti_stocks = array_unique($senti_stocks);

        $stock_counter = [];
        foreach ($senti_stocks as $key => $value) {
            $stock_counter[$value] = 0;
        }

        foreach ($sentiment_info as $key => $value) {
            $stock_counter[$value['stock_id']]++;
        }
        arsort($stock_counter);

        // add post values
        $base_info = [];
        foreach($stock_counter as $key => $value){
            $array_key = array_search($key, array_column($response['data'], 'id'));
            if($array_key !== false){
                $res_id = $response['data'][$array_key];
                if($res_id->symbol != "PSEI"){
                    $from_post = 0;
                    if(array_key_exists($res_id->symbol, $final_stock_list)){
                        $from_post = $final_stock_list[$res_id->symbol];
                    }
                    $base_info[$res_id->symbol] = $value + $from_post;
                }
            }
        }

        // insert post value
        foreach ($final_stock_list as $key => $value) {
            if(!array_key_exists($key, $base_info)){
                $base_info[$key] = $value;
            }
        }

        array_multisort($base_info, SORT_DESC); // get list of trending stocks
        
        $stock_information = [];
        $counter = 0;

        foreach ($base_info as $key => $value) {
            $trendinfo = [];
            
            $array_key = array_search($key, array_column($response['data'], 'symbol'));
            if(!empty($array_key)){
                $data_info = $response['data'][$array_key];
                
                $trendinfo['stock_id'] = $data_info->id_str;
                $trendinfo['market_code'] = $data_info->market_code;
                $trendinfo['description'] = $data_info->description;
                $trendinfo['hits'] = $value;

                array_push($stock_information, $trendinfo);
                $counter++;
            }
            if($counter == $limit){
                break;
            }
        }

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully fetched trending stocks.',
            'data' => [
                'stocks' => $stock_information
            ],
        ]);
    }
}
