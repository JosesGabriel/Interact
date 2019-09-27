<?php


namespace App\Data\QueryFilters\Post;

use Arbitrage\Abstracts\Pipelines\Eloquent\Filter;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class WallId
 *
 * @package App\Data\QueryFilters\Post
 */
class WallId extends Filter
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
