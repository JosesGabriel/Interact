<?php


namespace App\Data\Repositories\Sentiments;

use App\Data\Models\Sentiments\Sentiment;
use App\Data\Models\Sentiments\ChartSentiment;
use App\Data\Models\Posts\Post;
use App\Data\Models\Posts\Comments\Comment;
use App\Data\Repositories\BaseRepository;
use DateTime as DateTime; 

/**
 * Class SentimentRepository
 *
 * @package App\Data\Repositories\Sentiments
 */
class SentimentRepository extends BaseRepository
{
    /**
     * @var Sentiment
     */
    private $sentiment_model;
    private $chart_sentiment_model;
    private $post_model;
    private $comments_model;

    /**
     * SentimentRepository constructor.
     * @param Sentiment $sentiment
     */
    public function __construct(
        Sentiment $sentiment,
        ChartSentiment $chartSentiment,
        Post $postModel,
        Comment $commentModel
    ){
        $this->sentiment_model = $sentiment;
        $this->chart_sentiment_model = $chartSentiment;
        $this->post_model = $postModel;
        $this->comments_model = $commentModel;
    }

    /**
     * @param array $data
     * @return SentimentRepository
     */
    public function create(array $data)
    {
        $sentiment = $this->sentiment_model->init($data);

        //region Data validation
        if (!$sentiment->validate($data)) {
            $errors = $sentiment->getErrors();
            return $this->setResponse([
                'status' => 500,
                'message' => $errors[0],
                'meta' => [
                    'errors' => $errors,
                ],
            ]);
        }
        //endregion Data validation

        //region Data insertion
        if (!$sentiment->save()) {
            $errors = $sentiment->getErrors();
            return $this->setResponse([
                'status' => 500,
                'message' => 'An error has occurred while saving the post.',
                'meta' => [
                    'errors' => $errors,
                ],
            ]);
        }
        //endregion Data insertion

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully created sentiment.',
            'data' => [
                'sentiment' => $sentiment,
            ],
        ]);
    }

    /**
     * @param $id
     * @return SentimentRepository
     */
    public function delete($id)
    {
        //region Data validation
        if (!isset($id) ||
            !is_numeric($id)) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The sentiment id is not set or invalid.',
            ]);
        }
        //endregion Data validation

        //region Data deletion
        $sentiment = $this->sentiment_model->find($id);

        if (!$sentiment->delete()) {
            $errors = $sentiment->getErrors();
            return $this->setResponse([
                'status' => 500,
                'message' => 'An error has occurred while deleting the sentiment.',
                'meta' => [
                    'errors' => $errors,
                ],
            ]);
        }
        //endregion Data deletion

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully deleted the sentiment.',
        ]);
    }

    /**
     * @param $id
     * @return SentimentRepository
     */
    public function fetch($id)
    {
        //region Existence check
        $sentiment = $this->sentiment_model->find($id);

        if (!$sentiment) {
            return $this->setResponse([
                'status' => 404,
                'message' => 'The sentiment does not exist.',
            ]);
        }
        //endregion Existence check

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully fetched sentiment.',
            'data' => [
                'sentiment' => $sentiment,
            ],
        ]);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function search(array $data)
    {
        $model = Sentiment::query();

        //region Build query
        if (isset($data['where'])) {
            $model->where($data['where']);
        }

        if (isset($data['with'])) {
            $model->with($data['with']);
        }
        //endregion Build query

        $result = $model->get();
        $count = count($result);

        if ($count == 0) {
            return $this->setResponse([
                'status' => 404,
                'message' => 'Sentiments not found.',
                'meta' => [
                    'count' => [
                        'sentiments' => $count,
                    ],
                ],
            ]);
        }

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully searched sentiments.',
            'data' => [
                'sentiments' => $result,
            ],
            'meta' => [
                'count' => [
                    'sentiments' => $count,
                ],
            ],
        ]);
    }

    /**
     * @param $id
     * @param array $data
     * @return SentimentRepository
     */
    public function update($id, array $data)
    {
        //region Existence check
        $sentiment = $this->fetch($id);

        if ($sentiment->isError()) {
            return $sentiment;
        }
        //endregion Existence check

        $sentiment = $sentiment->getDataByKey('sentiment');

        //region Data update
        if (!$sentiment->save($data)) {
            $errors = $sentiment->getErrors();
            return $this->setResponse([
                'status' => 500,
                'message' => 'An error has occurred while updating the sentiment.',
                'meta' => [
                    'errors' => $errors,
                ],
            ]);
        }
        //endregion Data update

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully updated the sentiment.',
            'data' => [
                'sentiment' => $sentiment,
            ],
        ]);
    }

    public function check_chart(array $data) // check
    {
        date_default_timezone_set('Asia/Manila');
        $chart_sentiment = $this->chart_sentiment_model->where([["stock_id", "=", $data['stock_id']], ['user_id', '=', $data['user_id']]])->get()->toArray();
        
       
        if(empty($chart_sentiment)){
            // no sentiment has been placed on stock
            return false;
        } else {
            // there is sentiment placed,
            // check sentiment for date 
            // return true;
            $last_element = end($chart_sentiment); // get last sentimented information
            $date = date('Y-m-d H:i:s'); // get current date

            $d1 = new DateTime($date); 
            $d2 = new DateTime($last_element['created_at']);// last sentimented date

            $interval = $d1->diff($d2);
            $action_inverval = ($interval->days * 24) + $interval->h; // get interval

            if($action_inverval > 23){
                return false;
            } else {
                return true;
            }


        }
    }

    public function add_chart_sentiment(array $data)
    {
        $chart_sentiment = $this->chart_sentiment_model->init($data);
        $erros = [];
        if (!$chart_sentiment->validate($data)) {
            $errors = $chart_sentiment->getErrors();
            $erros['isgo'] = false;
            $erros['errors'] = $errors;
            // return $this->setResponse([
            //     'status' => 417,
            //     'message' => $errors[0],
            //     'meta' => [
            //         'errors' => $errors,
            //     ],
            // ]);
            return $erros;
        }

        if (!$chart_sentiment->save()) {
            $errors = $chart_sentiment->getErrors();
            $erros['isgo'] = false;
            $erros['errors'] = $errors;
            return $erros;
        }

        $erros['isgo'] = true;
        $erros['errors'] = "no errors";

        return $erros;
    }

    public function get_chart_sentiments(array $data)
    {
        // initiate bear/bull
        $bear = 0;
        $bull = 0;
        $total_sentiment = 0;

        // sentiments from chart
        // get sentiment as per stock
        $chart_sentiment = $this->chart_sentiment_model->where("stock_id", "=", $data['stock_id'])->get()->toArray();
        foreach ($chart_sentiment as $key => $value) {
            if($value['type'] == "bear"){
                $bear += 2;
            } else {
                $bull += 2;
            }
            $total_sentiment += 2;
        }

        // get sentiments from social posts
        // split information
        $stockinfo = explode(":", $data['market_code']);

        // get posts id with stock tags
        $sentiment_post_id = [];
        $post_sentiments = $this->post_model->where('content', 'like', '%$'.$stockinfo[1].'%')->get()->toArray();
        foreach ($post_sentiments as $key => $value) { array_push($sentiment_post_id, $value['id']); }

        // get post id as per tag in comments and replies
        $comment_sentiments = $this->comments_model->where('content', 'like', '%$'.$stockinfo[1].'%')->get()->toArray();
        foreach ($comment_sentiments as $key => $value) { array_push($sentiment_post_id, $value['id']); }
        
        // get post sentiments from $sentiment_post_id
        $post_sentiments = $this->sentiment_model->whereIn("sentimentable_id", $sentiment_post_id)->get()->toArray();

        
        // assign vote as per stock
        foreach ($post_sentiments as $key => $value) {
            if($value['type'] == "bear"){
                $bear++;
            } else {
                $bull++;
            }
            $total_sentiment++;
        }


        // get sentiments from comments

        $sentiment = [];
        $sentiment['bear'] = $bear;
        $sentiment['bull'] = $bull;
        $sentiment['total_sentiment'] = $total_sentiment;

        return $sentiment;
    }

    
}
