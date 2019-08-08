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
        'wall_id',
        'wall_type',
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

    //region Relations
    public function attachments()
    {
        return $this->morphMany(config('modelmap.attachments.attachment'), 'attachable');
    }

    public function comments()
    {
        return $this->hasMany(config('modelmap.posts.comments.comment'))->where('parent_id', 0);
    }
    //endregion Relations
}
