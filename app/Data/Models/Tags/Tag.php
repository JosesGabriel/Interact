<?php

namespace App\Data\Models\Tags;

use App\Data\Models\BaseModel;
use Snowflake\HasSnowflakePrimary;

/**
 * Class Tag
 *
 * @package App\Data\Models\Tags
 */
class Tag extends BaseModel
{
    use HasSnowflakePrimary;

    //region Configs
    public $incrementing = false;

    protected $casts = [
        'id' => 'string',
        'taggable_id' => 'string',
        'tag_meta' => 'array',
    ];

    protected $fillable = [
        'taggable_id',
        'taggable_type',
        'tag_id',
        'tag_type',
        'tag_meta'
    ];

    protected $hidden = [];

    protected $rules = [
        'taggable_id' => 'sometimes|required',
        'taggable_type' => 'sometimes|required',
        'tag_id' => 'sometimes|required',
        'tag_type' => 'sometimes|required',
        'tag_meta' => 'sometimes|array',
    ];
    //endregion Configs

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

    public function taggable()
    {
        return $this->morphTo();
    }
    //endregion Relations
}
