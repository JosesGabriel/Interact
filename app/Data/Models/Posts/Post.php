<?php


namespace App\Data\Models\Posts;

use App\Data\Models\BaseModel;
use App\Data\Models\Sentiments\HasSentiments;
use Illuminate\Database\Eloquent\SoftDeletes;
use Snowflake\HasSnowflakePrimary;

/**
 * Class Post
 *
 * @package App\Data\Models\Posts
 */
class Post extends BaseModel
{
    use HasSentiments;
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

    public function tags()
    {
        return $this->morphMany(config('arbitrage.models_map.tags.tag'), 'taggable');
    }

    public function taggedStocks()
    {
        return $this->tags()->where('tag_type', config('arbitrage.tags.model.tag_type.stock.value'));
    }

    public function taggedUsers()
    {
        return $this->tags()->where('tag_type', config('arbitrage.tags.model.tag_type.user.value'));
    }
    //endregion Relations
}
