<?php

namespace App\Data\Models\Tags;

trait HasTags
{
    //region Relations
    public function tags()
    {
        return $this->morphMany(config('arbitrage.models_map.tags.tag'), 'taggable');
    }

    public function taggedStocks()
    {
        return $this->tags()->where('tag_type', config('arbitrage.tags.model.tag_type.stock.value'));
    }

    public function taggedUsers()
    {
        return $this->tags()->where('tag_type', config('arbitrage.tags.model.tag_type.user.value'));
    }
    //endregion Relations
}
