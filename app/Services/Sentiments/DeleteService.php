<?php


namespace App\Services\Sentiments;

use App\Data\Repositories\Sentiments\SentimentRepository;
use App\Services\BaseService;

/**
 * Class DeleteService
 *
 * @package App\Services\Sentiments
 */
class DeleteService extends BaseService
{
    /**
     * @var SentimentRepository
     */
    private $sentiment_repo;

    /**
     * DeleteService constructor.
     * @param SentimentRepository $sentimentRepository
     */
    public function __construct(
        SentimentRepository $sentimentRepository
    ){
        $this->sentiment_repo = $sentimentRepository;
    }

    /**
     * @param array $data
     * @return SentimentRepository|DeleteService
     */
    public function handle(array $data)
    {
        //region Data validation
        if (!isset($data['sentimentable_id'])) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The target id is not set or invalid.',
            ]);
        }

        if (!isset($data['sentimentable_type'])) {
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

        if (!isset($data['type'])) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The sentiment type is not set or invalid.',
            ]);
        }
        //endregion Data validation

        //region Existence check
        $query['where'] = [
            ['sentimentable_id', '=', $data['sentimentable_id']],
            ['sentimentable_type', '=', $data['sentimentable_type']],
            ['type', '=', $data['type']],
            ['user_id', '=', $data['user_id']],
        ];

        $sentiments = $this->sentiment_repo->search($query);

        if ($sentiments->isError()) {
            return $this->setResponse([
                'status' => 404,
                'message' => 'The sentiment does not exist.',
            ]);
        }
        //endregion Existence check

        $sentiment = $sentiments->getDataByKey('sentiments')[0];

        //region Entity validation
        if ($sentiment->user_id != $data['user_id']) {
            return $this->setResponse([
                'status' => 403,
                'message' => 'The user is not allowed this action.',
            ]);
        }

        if ($sentiment->type != $data['type']) {
            return $this->setResponse([
                'status' => 403,
                'message' => "The user cannot un{$data['type']} the post.",
            ]);
        }
        //endregion Entity validation

        $response = $this->sentiment_repo->delete($sentiment->id);

        return $response;
    }
}
