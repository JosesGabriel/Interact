<?php

namespace App\Data\Providers;

use Arbitrage\Abstracts\Providers\BaseProvider as Provider;
/**
 * Class BaseProvider
 *
 * @package App\Data\Providers
 */
abstract class BaseProvider extends Provider
{
    abstract public function handle(array $config, array $data = []);
}