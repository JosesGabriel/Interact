<?php

namespace App\Events\Sentiments;

use App\Data\Models\Sentiments\Sentiment;
use Illuminate\Queue\SerializesModels;

/**
 * Class UserSentimentedEvent
 *
 * @package App\Events\Sentiments
 */
class UserSentimentedEvent
{
    use SerializesModels;

    /**
     * @var Sentiment
     */
    public $sentiment;

    /**
     * @var mixed
     */
    public $sentimentable;

    /**
     * @var array
     */
    public $sentimentable_data;

    /**
     * @var array
     */
    public $user_data;

    /**
     * Create a new event instance.
     *
     * @param Sentiment $sentiment
     */
    public function __construct(Sentiment $sentiment)
    {
        $this->sentiment = $sentiment;
        $this->sentimentable = $sentiment->sentimentable;
        $this->sentimentable_data = [
            'id' => $this->sentimentable->id,
            'bears' => $this->sentimentable->bears->count(),
            'bulls' => $this->sentimentable->bulls->count(),
        ];
        $this->user_data = [
            'id' => $sentiment->user_id,
        ];
    }
}
