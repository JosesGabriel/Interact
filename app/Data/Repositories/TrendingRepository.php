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

    /**
     * Post with $ Tags
     *
     * @param   int  $trending_days  number of scoped (days)
     *
     * @return  array 
     */
    public function getPostWithTags($trending_days)
    {
        $post_stocks = $this->post_model->where("content", "like", "%$%")->where('created_at', '>=', Carbon::now()->subDays($trending_days)->toDateTimeString())->get()->toArray();
        return $post_stocks;
    }

    /**
     * Comments with $ tags
     *
     * @param   int  $trending_days  number of scoped (days)
     *
     * @return  array                  
     */
    public function getCommentWithTags($trending_days)
    {
        $comment_stocks = $this->comments_model->where("content", "like", "%$%")->where('created_at', '>=', Carbon::now()->subDays($trending_days)->toDateTimeString())->get()->toArray();
        return $comment_stocks;
    }

    /**
     * get from chart sentiment
     *
     * @param   int  $trending_days  number of scoped (days)
     *
     * @return  array            
     */
    public function getChartSentiment($trending_days)
    {
        $sentiment_info = $this->chart_sentiment_model->where('created_at', '>=', Carbon::now()->subDays($trending_days)->toDateTimeString())->get()->toArray();
        return $sentiment_info;
    }

    /**
     * explode with multiple delimeters 
     *
     * @param   array   $delimiters  list of delimeters
     * 
     * @param   string   $string  value to be exploded
     * 
     * @return  array exploded values
     */
    public function multiexplode ($delimiters,$string) {

        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }

    /**
     * Posts Activity for n days
     *
     * @param   int  $trending_days  number of scoped (days)
     *
     * @return  array
     */
    public function getPostofTrending($trending_days)
    {
        $post_stocks = $this->post_model->where('created_at', '>=', Carbon::now()->subDays($trending_days)->toDateTimeString())->get()->toArray();
        return $post_stocks;
    }

    /**
     * get follower information
     *
     * @param   [type]  $key      [$key description]
     * @param   [type]  $user_id  [$user_id description]
     *
     * @return  [type]            [return description]
     */
    public function getFollowerInfo($key, $user_id)
    {
        $post_stocks = $this->follower->where([['user_id', "=", $key],['follower_id', "=", $user_id]])->get()->toArray();
        return $post_stocks;
    }

    /**
     * Get Following ids
     *
     * @param   int  $user_id  user id
     *
     * @return  array            list of follower ids
     */
    public function getFollowing($user_id)
    {
        return $this->follower->where('follower_id', "=", $user_id)->get()->toArray();
    }

    /**
     * Get Followers ids    
     *
     * @param   int  $user_id  user id
     *
     * @return  array   List of Follower ids
     */
    public function getFollowers($user_id)
    {
        return $this->follower->where('user_id', "=", $user_id)->get()->toArray();
    }
    
}
