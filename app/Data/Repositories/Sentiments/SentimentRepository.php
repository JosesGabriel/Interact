<?php


namespace App\Data\Repositories\Sentiments;

use App\Data\Models\Sentiments\Sentiment;
use App\Data\Repositories\BaseRepository;

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

    /**
     * SentimentRepository constructor.
     * @param Sentiment $sentiment
     */
    public function __construct(
        Sentiment $sentiment
    ){
        $this->sentiment_model = $sentiment;
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
}