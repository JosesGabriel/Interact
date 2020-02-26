<?php


namespace App\Data\Repositories;

use App\Data\Providers\DataProvider;
use App\Data\Models\Followers\Follower;
use App\Data\Models\Sentiments\Sentiment;
use App\Data\Models\Sentiments\ChartSentiment;
use App\Data\Models\Posts\Post;
use App\Data\Models\Posts\Comments\Comment;
use App\Data\Repositories\BaseRepository;
use DateTime as DateTime; 
use Carbon\Carbon;

/**
 * Class SentimentRepository
 *
 * @package App\Data\Repositories\Sentiments
 */
class TrendingRepository extends BaseRepository
{
    
    private $data_provider;
    private $sentiment_model;
    private $chart_sentiment_model;
    private $post_model;
    private $comments_model;
    private $follower;

    public function __construct(
        Sentiment $sentiment,
        ChartSentiment $chartSentiment,
        Post $postModel,
        Comment $commentModel,
        DataProvider $dataProvider,
        Follower $socialFollower
    ){
        $this->sentiment_model = $sentiment;
        $this->chart_sentiment_model = $chartSentiment;
        $this->post_model = $postModel;
        $this->comments_model = $commentModel;
        $this->data_provider = $dataProvider;
        $this->follower = $socialFollower;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data){

    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id){

    }

    /**
     * @param $id
     * @return mixed
     */
    public function fetch($id){

    }

    /**
     * @param array $data
     * @return mixed
     */
    public function search(array $data){

    }

    /**
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function update($id, array $data){

    }

    public function get_trending($data)
    {
        // init vals 
        $stocks = []; // list of stocks
        
        $trending_days = 150;
        $limit = (isset($data['count']) ? $data['count'] : 5);
        // $limit = 20;

        // post tags
        $post_stocks = $this->post_model->where("content", "like", "%$%")->where('created_at', '>=', Carbon::now()->subDays($trending_days)->toDateTimeString())->get()->toArray();
        foreach ($post_stocks as $key => $value) {
            $content = $value['content'];
            $segment_content = $this->multiexplode(array(" ","\r\n", "."),$content);
            foreach ($segment_content as $perkey => $pervalue) {
                if (strpos($pervalue, '$') !== false) {
                    array_push($stocks, strtoupper($pervalue));
                }
            }
        }

        // comment and reply tags
        $comment_stocks = $this->comments_model->where("content", "like", "%$%")->where('created_at', '>=', Carbon::now()->subDays($trending_days)->toDateTimeString())->get()->toArray();
        foreach ($comment_stocks as $key => $value) {
            $content = $value['content'];
            $segment_content = $this->multiexplode(array(" ","\r\n", "."),$content);
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
            'uri' => "/v2/stocks/list?exchange=PSE",
            "method" => "POST"
        ], [])->getResponse();
        $response['data'] = array_values($response['data']);
        // dd($response['data']);

        $sentiment_info = $this->chart_sentiment_model->where('created_at', '>=', Carbon::now()->subDays($trending_days)->toDateTimeString())->get()->toArray();
        
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

        // foreach ($stock_information as $key => $value) {
        //     dump($value['stock_id']);
        //     $sentiment_info = $this->chart_sentiment_model->where("stock_id", "=", $value['stock_id'])->get()->toArray();
        //     dump($sentiment_info);
        // }
        // dump($final_stock_list);

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully fetched trending stocks.',
            'data' => [
                'stocks' => $stock_information
            ],
        ]);
    }

    public function multiexplode ($delimiters,$string) {

        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }

    public function get_users($data)
    {
        $trending_days = 25;
        $limit = (isset($data['count']) ? $data['count'] : 5);
        // $limit = 10;
        $post_stocks = $this->post_model->where('created_at', '>=', Carbon::now()->subDays($trending_days)->toDateTimeString())->get()->toArray();

        if(empty($post_stocks)){
            return $this->setResponse([
                'status' => 400,
                'message' => 'No Activity for the past '.$trending_days.' days',
                'data' => [],
            ]);
        }

        $user_list = [];
        foreach ($post_stocks as $key => $value) { array_push($user_list, $value['user_id']); }

        $user_list_unique = array_unique($user_list);
        $user_counter = [];
        foreach ($user_list_unique as $key => $value) { $user_counter[$value] = 0; }
        foreach ($user_list as $key => $value) { $user_counter[$value]++; }
        arsort($user_counter);

        // removed current user 
        if(isset($data['user_id'])){
            unset($user_counter[$data['user_id']]);

            foreach ($user_counter as $key => $value) {
                $post_stocks = $this->follower->where([['user_id', "=", $key],['follower_id', "=", $data['user_id']]])->get()->toArray();
                if(!empty($post_stocks)){
                    unset($user_counter[$key]);
                }
            }
        }
        

        // $final_list = array_slice($user_counter, 0, $limit);

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully fetched Suggested Users.',
            'data' => [
                'users' => $user_counter
            ],
        ]);
    }

    public function getFollowing($user_id)
    {
        return $this->follower->where('follower_id', "=", $user_id)->get()->toArray();
    }

    public function getFollowers($user_id)
    {
        return $this->follower->where('user_id', "=", $user_id)->get()->toArray();
    }
    
}
