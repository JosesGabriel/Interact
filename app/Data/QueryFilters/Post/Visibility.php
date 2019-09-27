<?php


namespace App\Data\QueryFilters\Post;

use Arbitrage\Abstracts\Pipelines\Eloquent\Filter;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Visibility
 *
 * @package App\Data\QueryFilters\Post
 */
class Visibility extends Filter
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function applyFilter(Builder $builder): Builder
    {
        return $builder->where('visibility', request('visibility'));
    }
}
