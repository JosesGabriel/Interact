<?php


namespace App\Data\Models\Sentiments;

use App\Data\Models\BaseModel;
use Snowflake\HasSnowflakePrimary;

/**
 * Class Sentiment
 *
 * @package App\Data\Models\Sentiments
 */
class Sentiment extends BaseModel
{
    use HasSnowflakePrimary;

    //region Configs
    public $incrementing = false;

    protected $casts = [
        'id' => 'string',
        'sentimentable_id' => 'string',
        'user_id' => 'string',
    ];

    protected $fillable = [
        'sentimentable_id',
        'sentimentable_type',
        'user_id',
        'type',
    ];

    protected $hidden = [];

    protected $rules = [
        'sentimentable_id' => 'sometimes|required',
        'sentimentable_type' => 'sometimes|required',
        'user_id' => 'sometimes|required|uuid',
        'type' => 'sometimes|required',
    ];
    //endregion Configs

    //region Relations
    public function sentimentable()
    {
        return $this->morphTo();
    }
    //endregion Relations
}
