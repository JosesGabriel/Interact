<?php


namespace App\Data\Models\Sentiments;

use App\Data\Models\BaseModel;
use Snowflake\HasSnowflakePrimary;

/**
 * Class Sentiment
 *
 * @package App\Data\Models\Sentiments
 */
class ChartSentiment extends BaseModel
{
    use HasSnowflakePrimary;

    //region Configs
    public $incrementing = false;

    protected $casts = [
        'id' => 'string',
        'stock_id' => 'string',
        'user_id' => 'string',
    ];

    protected $fillable = [
        'stock_id',
        'user_id',
        'type',
    ];

    protected $hidden = [];

    protected $rules = [
        'stock_id' => 'sometimes|required',
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
