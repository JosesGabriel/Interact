<?php


namespace App\Data\Models\Posts\Comments;

use App\Data\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Snowflake\HasSnowflakePrimary;

/**
 * Class Comment
 *
 * @package App\Data\Models\Posts\Comments
 */
class Comment extends BaseModel
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
        'post_id',
        'parent_id',
        'user_id',
        'content',
    ];

    protected $hidden = [];

    protected $rules = [
        'post_id' => 'sometimes|required',
        'parent_id' => 'sometimes|required',
        'user_id' => 'sometimes|required',
        'content' => 'sometimes|required',
    ];
    //endregion Configs

    //region Relations
    public function post()
    {
        return $this->belongsTo(config('modelmap.posts.post'));
    }

    public function comments()
    {
        return $this->hasMany(config('modelmap.posts.comments.comment'), 'parent_id', 'id');
    }
    //endregion Relations
}
