<?php

namespace App\Services\Samples;

use App\Repositories\Samples\SampleRepository;

class SampleService
{
    protected
        $sample_repo;

    public function __construct(
        SampleRepository $sampleRepository
    ) {
        $this->sample_repo = $sampleRepository;
    }

    /**
     * @param Array $data
     * @return Array
     */
    public function fetch($data = [])
    {
        // * insert any checks here
        // * or change behavior depending on input data

        $result = $this->sample_repo->fetch($data);

        return $result;
    }

    /**
     * @param Array $data
     * @return Array
     */
    public function make($data = [])
    {
        // * insert any business logic here

        // * like calling up some validator class
        // $valid = $this->sample_validator->create($data);
        // if (!$valid->success) {
        //     return $valid;
        // }

        // * or like push notifications to users
        // $this->notificationEvent->push($data);

        // * or email event
        // $this->emailEvent->sampleEmail($data);

        // * store it in our database
        $result = $this->sample_repo->create($data);

        return $result;
    }
}
