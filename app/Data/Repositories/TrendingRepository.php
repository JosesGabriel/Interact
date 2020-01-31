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
        
        $trending_days = 182;
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

        array_multisort($final_stock_list, SORT_DESC); // get list of trending stocks
        

        $response = $this->data_provider->handle([
            'uri' => "/v2/stocks/history/latest?exchange=PSE&type=stock",
            "method" => "POST"
        ], [])->getResponse();

        $stock_information = [];
        $counter = 0;

        foreach ($final_stock_list as $key => $value) {
            $trendinfo = [];
            
            $array_key = array_search($key, array_column($response['data'], 'symbol'));
            if(!empty($array_key)){
                $data_info = $response['data'][$array_key];
                
                $trendinfo['stock_id'] = $data_info->stockidstr;
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

    public function multiexplode ($delimiters,$string) {

        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }

    public function get_users($data)
    {
        $trending_days = 90;
        // $limit = (isset($data['count']) ? $data['count'] : 5);
        $limit = 20;
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
                $post_stocks = $this->follower->where([['user_id', "=", $data['user_id']],['follower_id', "=", $key]])->get()->toArray();
                if(!empty($post_stocks)){
                    unset($user_counter[$key]);
                }
            }
        }

        $final_list = array_slice($user_counter, 0, $limit);

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully fetched Suggested Users.',
            'data' => [
                'users' => $final_list
            ],
        ]);
    }
    
}
