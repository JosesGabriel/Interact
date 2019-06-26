<?php

namespace App\Repositories\Samples;

use App\Interfaces\Repositories\SampleRepositoryInterface;
use App\Repositories\BaseRepository;

class SampleRepository extends BaseRepository
{
    public function create(array $data)
    {
        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully created.',
            'meta' => [],
            'data' => [
                'sample' => [],
            ],
        ]);
    }

    public function fetch(array $data)
    {
        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully fetchd.',
            'meta' => [],
            'data' => [
                'sample' => [],
            ],
        ]);
    }

    public function delete(int $id)
    {
        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully deleted.',
            'meta' => [],
            'data' => [
                'sample' => [],
            ],
        ]);
    }

    public function search(array $data)
    {
        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully searchd.',
            'meta' => [],
            'data' => [
                'sample' => [],
            ],
        ]);
    }

    public function update(int $id, array $data)
    {
        return $this->setResponse([
            'status' => 200,
            'message' => 'Successfully updated.',
            'meta' => [],
            'data' => [
                'sample' => [],
            ],
        ]);
    }
}
