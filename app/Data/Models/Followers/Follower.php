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
}
