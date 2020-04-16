<?php

namespace App\Data\Models;

use Arbitrage\Abstracts\Models\EloquentModel;

/**
 * Class BaseModel
 *
 * @package App\Data\Models
 */
class BaseModel extends EloquentModel
{
    /**
     * BaseModel constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setDebugMode(config('app.debug'));
    }
}
