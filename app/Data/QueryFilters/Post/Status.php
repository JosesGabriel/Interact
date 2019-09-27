<?php


namespace App\Data\QueryFilters\Post;

use Arbitrage\Abstracts\Pipelines\Eloquent\Filter;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Status
 *
 * @package App\Data\QueryFilters\Post
 */
class Status extends Filter
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function applyFilter(Builder $builder): Builder
    {
        return $builder->where('status', request('status'));
    }
}
