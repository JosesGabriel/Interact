<?php


namespace App\Data\Repositories;

use App\Data\Providers\DataProvider;
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

    public function __construct(
        Sentiment $sentiment,
        ChartSentiment $chartSentiment,
        Post $postModel,
        Comment $commentModel,
        DataProvider $dataProvider
    ){
        $this->sentiment_model = $sentiment;
        $this->chart_sentiment_model = $chartSentiment;
        $this->post_model = $postModel;
        $this->comments_model = $commentModel;
        $this->data_provider = $dataProvider;
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

    public function get_trending()
    {
        // init vals 
        $stocks = []; // list of stocks
        
        $trending_days = 182;
        $limit = (isset($data['count']) ? $data['count'] : 10) ;
        dump($limit);

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

        $stock_information = [];
        $counter = 0;
        foreach ($final_stock_list as $key => $value) {
            // $response = $this->data_provider->handle([
            //     'uri' => "api/v1/stocks/history/latest?exchange=PSE&symbol=".$value,
            //     "method" => "GET"
            // ], [])->getResponse();

            // if($counter == 2){
            //     break;
            // }
            // $counter++;
        }

        // $response = $this->data_provider->handle([
        //     'uri' => "/api/v2/stocks/history/latest?symbol-id=".$value['stock_id'],
        //     'uri' => "api/v1/stocks/history/latest?exchange=PSE&symbol=DAVIN".$value['stock_id'],
        //     "method" => "GET"
        // ], [])->getResponse();



        
        dump($final_stock_list);
    }

    public function multiexplode ($delimiters,$string) {

        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }
    
}
