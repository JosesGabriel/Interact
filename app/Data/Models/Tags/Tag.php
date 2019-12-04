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
    ];

    protected $fillable = [
        'taggable_id',
        'taggable_type',
        'tag_id',
        'tag_type',
    ];

    protected $hidden = [];

    protected $rules = [
        'taggable_id' => 'sometimes|required',
        'taggable_type' => 'sometimes|required',
        'tag_id' => 'sometimes|required',
        'tag_type' => 'sometimes|required',
    ];
    //endregion Configs

    //region Relations
    public function taggable()
    {
        return $this->morphTo();
    }
    //endregion Relations
}
