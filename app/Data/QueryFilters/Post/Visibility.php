<?php


namespace App\Data\QueryFilters\Post;

use App\Data\QueryFilters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Visibility
 *
 * @package App\Data\QueryFilters\Post
 */
class Visibility extends BaseFilter
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
