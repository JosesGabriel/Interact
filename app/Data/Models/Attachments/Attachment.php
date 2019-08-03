<?php


namespace App\Data\Models\Attachments;

use App\Data\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Snowflake\HasSnowflakePrimary;

/**
 * Class Attachment
 *
 * @package App\Data\Models\Attachments
 */
class Attachment extends BaseModel
{
    use HasSnowflakePrimary;
    use SoftDeletes;

    //region Configs
    public $incrementing = false;

    protected $casts = [
        'id' => 'string',
        'attachable_id' => 'string',
    ];

    protected $hidden = [];

    protected $fillable = [
        'attachable_id',
        'attachable_type',
        'user_id',
        'url',
    ];

    protected $rules = [
        'attachable_id' => 'sometimes|required',
        'attachable_type' => 'sometimes|required',
        'user_id' => 'sometimes|required|uuid',
        'url' => 'sometimes|required|url',
    ];
    //endregion Configs

    //region Relations
    public function attachable()
    {
        return $this->morphTo();
    }
    //endregion Relations
}
