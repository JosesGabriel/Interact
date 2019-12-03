<?php

namespace App\Data\Models\Followers;

use App\Data\Models\BaseModel;
use Snowflake\HasSnowflakePrimary;

/**
 * Class Follower
 *
 * @package App\Data\Models\Followers
 */
class Follower extends BaseModel
{
    use HasSnowflakePrimary;

    //region Configs
    public $incrementing = false;

    protected $casts = [
        'id' => 'string',
    ];

    protected $fillable = [
        'user_id',
        'follower_id',
    ];

    protected $hidden = [
        'id',
    ];

    protected $rules = [
        'users_id' => 'sometimes|required|uuid',
        'follower_id' => 'sometimes|required|uuid',
    ];
    //endregion Configs

    //region Scopes
    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $user_id
     * @param $follower_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsFollower($query, $user_id, $follower_id)
    {
        return $query->addSelect([
            'is_follower' => function ($subquery) use ($user_id, $follower_id) {
                $subquery
                    ->selectRaw('count(*)')
                    ->from('followers')
                    ->where('user_id', $user_id)
                    ->where('follower_id', $follower_id)
                    ->first();
            },
        ]);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $follow_id
     * @param $follower_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsFollowing($query, $follow_id, $follower_id)
    {
        return $query->addSelect([
            'is_following' => function ($subquery) use ($follow_id, $follower_id) {
                $subquery
                    ->selectRaw('count(*)')
                    ->from('followers')
                    ->where('user_id', $follow_id)
                    ->where('follower_id', $follower_id)
                    ->first();
            },
        ]);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $user_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeProfile($query, $user_id)
    {
        return $query->addSelect([
            'followers' => function ($subquery) use ($user_id) {
                $subquery
                    ->selectRaw('count(*)')
                    ->from('followers')
                    ->where('user_id', $user_id);
            },
            'following' => function ($subquery) use ($user_id) {
                $subquery
                    ->selectRaw('count(*)')
                    ->from('followers')
                    ->where('follower_id', $user_id);
            },
            'posts' => function ($subquery) use ($user_id) {
                $subquery
                    ->selectRaw('count(*)')
                    ->from('posts')
                    ->where('user_id', $user_id);
            },
        ]);
    }
    //endregion Scopes
}
