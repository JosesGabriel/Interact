<?php


namespace App\Data\QueryFilters\Post;

use App\Data\QueryFilters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class UserId
 *
 * @package App\Data\QueryFilters\Post
 */
class UserId extends BaseFilter
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function applyFilter(Builder $builder): Builder
    {
        return $builder->where('user_id', request('user_id'));
    }
}
