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

    public function sentiments()
    {
        return $this->morphMany(config('arbitrage.models_map.sentiments.sentiment'), 'sentimentable');
    }
    //endregion Relations
}
