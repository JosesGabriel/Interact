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
class SuggestedUserService extends BaseService
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
        $trending_days = 25;
        $limit = (isset($data['count']) ? $data['count'] : 5);

        $post_stocks = $this->trending_repo->getPostofTrending($trending_days);

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
                $post_stocks = $this->trending_repo->getFollowerInfo($key, $data['user_id']);
                if(!empty($post_stocks)){
                    unset($user_counter[$key]);
                }
            }
        }

        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully fetched Suggested Users.',
            'data' => [
                'users' => $user_counter
            ],
        ]);
    }
}
