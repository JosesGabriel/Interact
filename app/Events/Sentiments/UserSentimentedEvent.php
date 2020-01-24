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
     * Create a new event instance.
     *
     * @param Sentiment $sentiment
     */
    public function __construct(Sentiment $sentiment)
    {
        $this->sentiment = $sentiment;
    }
}
