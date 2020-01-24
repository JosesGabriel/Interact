<?php

namespace App\Data\Models\Tags;

use App\Data\Models\BaseModel;
use App\Data\Models\Sentiments\HasSentiments;
use Snowflake\HasSnowflakePrimary;

/**
 * Class Tag
 *
 * @package App\Data\Models\Tags
 */
class Tag extends BaseModel
{
    use HasSentiments;
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
    public function taggable()
    {
        return $this->morphTo();
    }
    //endregion Relations
}
