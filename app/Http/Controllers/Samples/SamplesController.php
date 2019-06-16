<?php

namespace App\Http\Controllers\Samples;

use App\Http\Controllers\BaseController;
use App\Services\Samples\SampleService;
use Illuminate\Http\Request;

class SamplesController extends BaseController
{
    protected
        $sample_service;

    public function __construct(
        SampleService $sampleService
    ) {
        $this->sample_service = $sampleService;
    }

    /**
     * Fetches all sample data
     *
     * @param \Illuminate\Http\Response $request
     * @return Array
     */
    public function all(Request $request)
    {
        $data = $this->sample_service->fetch($request->all());

        return $this->absorb($data)->respond();
    }

    /**
     * Create a sample data
     *
     * @param \Illuminate\Http\Response $request
     * @return Array
     */
    public function create(Request $request)
    {
        // call up the service that will handle the creation logic
        $data = $this->sample_service->make($request->all());

        return $this->absorb($data)->respond();
    }
}
