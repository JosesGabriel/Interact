<?php

namespace App\Data\QueryFilters\Post;

use App\Data\QueryFilters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class OrderBy
 *
 * @package App\Data\QueryFilters\Post
 */
class OrderBy extends BaseFilter
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function applyFilter(Builder $builder): Builder
    {
        $direction = request('order') ?? 'DESC';
        return $builder->orderBy(request('order_by'), $direction);
    }
}
