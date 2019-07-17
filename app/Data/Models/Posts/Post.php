<?php


namespace App\Data\Models\Posts;

use App\Data\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Class Post
 *
 * @package App\Data\Models\Posts
 */
class Post extends BaseModel
{
    use SoftDeletes;

    //region Configs
    protected $fillable = [
        'user_id',
        'content',
        'status',
        'visibility',
    ];

    protected $hidden = [
        'id',
    ];

    protected $rules = [
        'user_id' => 'sometimes|required',
        'content' => 'sometimes|required',
        'status' => 'sometimes|required',
        'visibility' => 'sometimes|required',
    ];
    //endregion Configs

    //region Finders
    /**
     * @param string $uuid
     * @return mixed
     */
    public static function findByUUID($uuid)
    {
        return static::byUUID($uuid)->first();
    }
    //endregion Finders

    //region Scopes
    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $uuid
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUUID($query, $uuid)
    {
        return $query->where('uuid', $uuid);
    }
    //endregion Scopes

    //region Events
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->uuid = Str::uuid()->toString();
        });
    }
    //endregion Events
}
