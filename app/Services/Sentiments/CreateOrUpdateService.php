<?php


namespace App\Services\Sentiments;

use App\Data\Repositories\Sentiments\SentimentRepository;
use App\Events\Sentiments\UserSentimentedEvent;
use App\Services\BaseService;

/**
 * Class CreateOrUpdateService
 *
 * @package App\Services\Sentiments
 */
class CreateOrUpdateService extends BaseService
{
    /**
     * @var SentimentRepository
     */
    private $sentiment_repo;

    /**
     * CreateOrUpdateService constructor.
     * @param SentimentRepository $sentimentRepository
     */
    public function __construct(
        SentimentRepository $sentimentRepository
    ){
        $this->sentiment_repo = $sentimentRepository;
    }

    /**
     * @param array $data
     * @return SentimentRepository|CreateOrUpdateService
     */
    public function handle(array $data) : object
    {
        $model_config = config('arbitrage.sentiments.model');

        //region Data validation
        if (!isset($data['sentimentable_id'])) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The target id is not set or invalid.',
            ]);
        }

        $valid_models = array_keys($model_config['sentimentable_type']);

        if (!isset($data['sentimentable_type']) ||
            !in_array($data['sentimentable_type'], $valid_models)) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The target type is not set or invalid.',
            ]);
        }

        if (!isset($data['user_id'])) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The user id is not set or invalid.',
            ]);
        }

        $valid_types = array_keys($model_config['type']);

        if (!isset($data['type']) ||
            !in_array($data['type'], $valid_types)) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The sentiment type is not set or invalid.',
            ]);
        }
        //endregion Data validation

        $data['sentimentable_type'] = $model_config['sentimentable_type'][$data['sentimentable_type']]['value'];

        //region Existence check
        $query['where'] = [
            ['user_id', '=', $data['user_id']],
            ['sentimentable_id', '=', $data['sentimentable_id']],
            ['sentimentable_type', '=', $data['sentimentable_type']],
        ];

        $result = $this->sentiment_repo->search($query);
        $sentiments = $result->getDataByKey('sentiments');
        //endregion Existence check

        //region Create or update
        // if existing
        if ($result->isSuccess()) {
            $sentiment = $sentiments->first();

            // if of the same type
            if ($sentiment->type == $data['type']) {
                return $this->setResponse([
                    'status' => 403,
                    'message' => "The user has already {$data['type']} the post.",
                ]);
            }
            // if of different type
            else {
                $response = $this->sentiment_repo->update($sentiment->id, $data);
            }
        }
        // if not existing
        else {
            $response = $this->sentiment_repo->create($data);
        }
        //endregion Create or update

        if ($response->isError()) {
            return $response;
        }

        $sentiment = $response->getDataByKey('sentiment');

        event(new UserSentimentedEvent($sentiment));

        return $response;
    }
}
