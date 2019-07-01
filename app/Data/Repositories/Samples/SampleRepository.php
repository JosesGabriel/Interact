<?php

namespace App\Data\Repositories\Samples;

use App\Interfaces\Repositories\SampleRepositoryInterface;
use App\Repositories\BaseRepository;

/**
 * Class SampleRepository
 *
 * @package App\Data\Repositories\Samples
 */
class SampleRepository extends BaseRepository
{
    /**
     * @param array $data
     * @return SampleRepository
     */
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

    /**
     * @param array $data
     * @return SampleRepository
     */
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

    /**
     * @param int $id
     * @return SampleRepository
     */
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

    /**
     * @param array $data
     * @return SampleRepository
     */
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

    /**
     * @param int $id
     * @param array $data
     * @return SampleRepository
     */
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
