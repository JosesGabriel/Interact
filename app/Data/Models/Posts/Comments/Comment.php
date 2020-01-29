<?php


namespace App\Data\Models\Posts\Comments;

use App\Data\Models\BaseModel;
use App\Data\Models\Sentiments\HasSentiments;
use App\Data\Models\Tags\HasTags;
use Illuminate\Database\Eloquent\SoftDeletes;
use Snowflake\HasSnowflakePrimary;

/**
 * Class Comment
 *
 * @package App\Data\Models\Posts\Comments
 */
class Comment extends BaseModel
{
    use HasSentiments;
    use HasSnowflakePrimary;
    use HasTags;
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
        'post_id' => 'string',
        'parent_id' => 'string',
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
    public function parentComment()
    {
        return $this->belongsTo(config('arbitrage.models_map.posts.comments.comment'), 'id', 'parent_id');
    }

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
