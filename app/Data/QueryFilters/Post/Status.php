<?php


namespace App\Data\QueryFilters\Post;

use App\Data\QueryFilters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Status
 *
 * @package App\Data\QueryFilters\Post
 */
class Status extends BaseFilter
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
