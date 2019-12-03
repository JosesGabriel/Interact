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
     * @param $profile_id
     * @param $user_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMyFollower($query, $profile_id, $user_id)
    {
        return $query->addSelect([
            'my_follower' => function ($subquery) use ($profile_id, $user_id) {
                $subquery
                    ->selectRaw('count(*)')
                    ->from('followers')
                    ->where('user_id', $user_id)
                    ->where('follower_id', $profile_id)
                    ->first();
            },
        ]);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $profile_id
     * @param $user_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeImFollowing($query, $profile_id, $user_id)
    {
        return $query->addSelect([
            'im_following' => function ($subquery) use ($profile_id, $user_id) {
                $subquery
                    ->selectRaw('count(*)')
                    ->from('followers')
                    ->where('user_id', $profile_id)
                    ->where('follower_id', $user_id)
                    ->first();
            },
        ]);
    }

    /**
     * TODO: Separate Posts count for User
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
