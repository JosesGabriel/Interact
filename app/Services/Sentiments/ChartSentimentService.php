<?php


namespace App\Services\Sentiments;


use App\Data\Repositories\Sentiments\SentimentRepository;
use App\Services\BaseService;

/**
 * Class ChartSentimentService
 *
 * @package App\Services\Sentiments
 */
class ChartSentimentService extends BaseService
{
    /**
     * @var SentimentRepository
     */
    private $chartsentiment_repo;

    /**
     * ChartSentimentService constructor.
     * @param SentimentRepository $sentimentRepository
     */
    public function __construct(
        SentimentRepository $ChartSentimentModel
    ){
        $this->chartsentiment_repo = $ChartSentimentModel;
    }

    /**
     * @param array $data
     * @return SentimentRepository|CreateOrUpdateService
     */
    public function handle(array $data) : object
    {
        if(!isset($data['stock_id'])){
            return $this->setResponse([
                'status' => 400,
                'message' => 'Missing Stock ID',
                'data' => $data
            ]);
        }
        if($data['action'] == "add"){
            // check if the user already added a stock sentiment per day
            // true -> sentiment already added
            // false -> no sentiment added
            $checking = $this->chartsentiment_repo->check_chart($data);
            if(!$checking){ // add sentiment if returns false
                $data['type'] = $data['sentiment'];
                $adding_sentiment = $this->chartsentiment_repo->add_chart_sentiment($data);
                $sentiment_value = $this->chartsentiment_repo->get_chart_sentiments($data);
                
                return $this->setResponse([
                    'status' => 200,
                    'message' => 'Successfully added a sentiment.',
                    'data' => [
                        'sentiment' => $sentiment_value,
                    ],
                ]);
            } else{
                $sentiment_value = $this->chartsentiment_repo->get_chart_sentiments($data);

                return $this->setResponse([
                    'status' => 200,
                    'message' => 'No sentiment was added',
                    'data' => [
                        'sentiment' => $sentiment_value,
                    ],
                ]);
            }
        } else {
            $sentiment_value = $this->chartsentiment_repo->get_chart_sentiments($data);

            return $this->setResponse([
                'status' => 200,
                'message' => 'Successfully fetched sentiment.',
                'data' => [
                    'sentiment' => $sentiment_value,
                ],
            ]);
        }
        

        
    }
}
