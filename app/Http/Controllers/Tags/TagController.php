<?php

namespace App\Http\Controllers\Tags;

use App\Http\Controllers\BaseController;
use App\Services\Sentiments\CreateOrUpdateService as CreateSentimentService;
use App\Services\Tags\CreateService;
use App\Services\Tags\DeleteService;
use Illuminate\Http\Request;

/**
 * Class TagController
 *
 * @package App\Http\Controllers\Tags
 */
class TagController extends BaseController
{
    /**
     * @param Request $request
     * @param CreateService $createService
     * @return \Illuminate\Http\Response
     */
    public function create(
        Request $request,
        CreateService $createService
    ){
        $data = $request->all();

        $response = $createService->handle($data);

        return $this->absorb($response)->respond();
    }

    /**
     * @param DeleteService $deleteService
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function delete(
        DeleteService $deleteService,
        $id
    ){
        $data['id'] = $id;

        $response = $deleteService->handle($data);

        return $this->absorb($response)->respond();
    }

    /**
     * @param Request $request
     * @param CreateSentimentService $createSentimentService
     * @param $id
     * @param $sentiment
     * @return \Illuminate\Http\Response
     */
    public function sentimentalize(
        Request $request,
        CreateSentimentService $createSentimentService,
        $id,
        $sentiment
    ){
        $data = $request->all();

        $data['sentimentable_id'] = $id;
        $data['sentimentable_type'] = config('arbitrage.sentiments.model.sentimentable_type.tag.value');
        $data['type'] = $sentiment;

        $response = $createSentimentService->handle($data);

        return $this->absorb($response)->respond();
    }

    /**
     * @param Request $request
     * @param \App\Services\Sentiments\DeleteService $deleteService
     * @param $id
     * @param $sentiment
     * @return \Illuminate\Http\Response
     */
    public function unsentimentalize(
        Request $request,
        \App\Services\Sentiments\DeleteService $deleteService,
        $id,
        $sentiment
    ){
        $data = $request->all();

        $data['sentimentable_id'] = $id;
        $data['sentimentable_type'] = config('arbitrage.sentiments.model.sentimentable_type.tag.value');
        $data['type'] = $sentiment;

        $response = $deleteService->handle($data);

        return $this->absorb($response)->respond();
    }
}
