<?php


namespace App\Data\Models\Posts;

use App\Data\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Snowflake\HasSnowflakePrimary;

/**
 * Class Post
 *
 * @package App\Data\Models\Posts
 */
class Post extends BaseModel
{
    use HasSnowflakePrimary;
    use SoftDeletes;

    //region Configs
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    protected $casts = [
        'id' => 'string',
    ];

    protected $fillable = [
        'user_id',
        'content',
        'status',
        'visibility',
    ];

    protected $hidden = [];

    protected $rules = [
        'user_id' => 'sometimes|required',
        'content' => 'sometimes|required',
        'status' => 'sometimes|required',
        'visibility' => 'sometimes|required',
    ];
    //endregion Configs
}
