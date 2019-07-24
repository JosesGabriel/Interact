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
        if (!isset($data['id'])) {
            return $this->setResponse([
                'status' => 417,
                'message' => 'The sentiment id is not set or invalid.',
            ]);
        }

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
        $sentiment = $this->sentiment_repo->fetch($data['id']);

        if ($sentiment->isError()) {
            return $sentiment;
        }
        //endregion Existence check

        $sentiment = $sentiment->getDataByKey('sentiment');

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
