<?php


namespace App\Services;


use App\Data\Repositories\TrendingRepository;
use App\Services\BaseService;

/**
 * Class ChartSentimentService
 *
 * @package App\Services\Sentiments
 */
class TrendingService extends BaseService
{
    /**
     * @var SentimentRepository
     */
    private $trending_repo;

    /**
     * ChartSentimentService constructor.
     * @param SentimentRepository $sentimentRepository
     */
    public function __construct(
        TrendingRepository $trendingModel
    ){
        $this->trending_repo = $trendingModel;
    }

    /**
     * @param array $data
     * @return SentimentRepository|CreateOrUpdateService
     */
    public function handle(array $data) : object
    {
        if(isset($data['type'])){
            if($data['type'] == "users"){
                $trending_stocks = $this->trending_repo->get_users($data);
            }
        } else {
            $trending_stocks = $this->trending_repo->get_trending($data);
        }
        
        return $trending_stocks;
    }
}
