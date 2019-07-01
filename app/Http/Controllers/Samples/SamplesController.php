<?php

namespace App\Http\Controllers\Samples;

use App\Http\Controllers\BaseController;
use App\Services\Samples\SampleService;
use Illuminate\Http\Request;

/**
 * Class SamplesController
 *
 * @package App\Http\Controllers\Samples
 */
class SamplesController extends BaseController
{
    /**
     * @var SampleService
     */
    protected $sample_service;

    /**
     * SamplesController constructor.
     * @param SampleService $sampleService
     */
    public function __construct(
        SampleService $sampleService
    ) {
        $this->sample_service = $sampleService;
    }

    /**
     * Fetches all sample data
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function all(Request $request)
    {
        $data = $this->sample_service->fetch($request->all());

        return $this->absorb($data)->respond();
    }

    /**
     * Create a sample data
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // call up the service that will handle the creation logic
        $data = $this->sample_service->make($request->all());

        return $this->absorb($data)->respond();
    }
}
