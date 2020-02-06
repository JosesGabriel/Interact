<?php


namespace App\Data\QueryFilters\Post;

use App\Data\QueryFilters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class WallId
 *
 * @package App\Data\QueryFilters\Post
 */
class WallId extends BaseFilter
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function applyFilter(Builder $builder): Builder
    {
        return $builder->where('wall_id', request('wall_id'));
    }
}
