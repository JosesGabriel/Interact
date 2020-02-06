<?php

namespace App\Data\QueryFilters\Post;

use App\Data\QueryFilters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Page
 *
 * @package App\Data\QueryFilters\Post
 */
class Page extends BaseFilter
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function applyFilter(Builder $builder): Builder
    {
        $limit = request('limit') ?? config('arbitrage.posts.config.query.limit');
        $page = request('page') ?? 1;
        $offset = $limit * ($page - 1);
        return $builder->limit($limit)->offset($offset);
    }
}
