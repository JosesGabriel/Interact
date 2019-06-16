<?php

namespace App\Repositories\Samples;

use App\Interfaces\Repositories\SampleRepositoryInterface;

class SampleRepository implements SampleRepositoryInterface
{
    public function create(array $data) : array
    {
        return [];
    }

    public function fetch(array $data) : array
    {
        return [];
    }

    public function delete(int $id) : array
    {
        return [];
    }

    public function search(array $data) : array
    {
        return [];
    }

    public function update(array $data) : array
    {
        return [];
    }
}
