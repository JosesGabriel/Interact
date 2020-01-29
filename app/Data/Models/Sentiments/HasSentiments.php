<?php

namespace App\Data\Models\Sentiments;

trait HasSentiments
{
    //region Relations
    public function bears()
    {
        return $this->morphMany(config('arbitrage.models_map.sentiments.sentiment'), 'sentimentable')
            ->where('type', config('arbitrage.sentiments.model.type.bear.value'));
    }

    public function bulls()
    {
        return $this->morphMany(config('arbitrage.models_map.sentiments.sentiment'), 'sentimentable')
            ->where('type', config('arbitrage.sentiments.model.type.bull.value'));
    }

    /**
     * Alias for authorized user sentiments for eager loading relations
     * @return mixed
     */
    public function mySentiment()
    {
        return $this->morphOne(config('arbitrage.models_map.sentiments.sentiment'), 'sentimentable');
    }

    public function sentiments()
    {
        return $this->morphMany(config('arbitrage.models_map.sentiments.sentiment'), 'sentimentable');
    }
    //endregion Relations
}
