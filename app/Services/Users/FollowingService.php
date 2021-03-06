<?php


namespace App\Services\Users;


use App\Data\Repositories\TrendingRepository;
use App\Services\BaseService;

/**
 * Class ChartSentimentService
 *
 * @package App\Services\Sentiments
 */
class FollowingService extends BaseService
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
        $follower_id = $this->trending_repo->getFollowing($data['user_id']);
        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully fetched Following ID.',
            'data' => [
                'users' => $follower_id
            ],
        ]);
    }
}
