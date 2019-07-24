<?php


namespace App\Http\Controllers\Posts;

use App\Http\Controllers\BaseController;
use App\Services\Posts\CreateService;
use App\Services\Posts\DeleteService;
use App\Services\Posts\FetchService;
use App\Services\Posts\UpdateService;
use App\Services\Sentiments\CreateOrUpdateService;
use Illuminate\Http\Request;

/**
 * Class PostsController
 *
 * @package App\Http\Controllers\Posts
 */
class PostsController extends BaseController
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
     * @param Request $request
     * @param DeleteService $deleteService
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function delete(
        Request $request,
        DeleteService $deleteService,
        $id
    ){
        $data = $request->all();

        $data['id'] = $id;

        $response = $deleteService->handle($data);

        return $this->absorb($response)->respond();
    }

    /**
     * Fetch a post by id
     *
     * @param Request $request
     * @param FetchService $fetchService
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function fetch(
        Request $request,
        FetchService $fetchService,
        $id
    ){
        $data = $request->all();

        $data['id'] = $id;

        $response = $fetchService->handle($data);

        return $this->absorb($response)->respond();
    }

    /**
     * @param Request $request
     * @param CreateOrUpdateService $createOrUpdateService
     * @param $id
     * @param $sentiment
     * @return \Illuminate\Http\Response
     */
    public function sentimentalize(
        Request $request,
        CreateOrUpdateService $createOrUpdateService,
        $id,
        $sentiment
    ){
        $data = $request->all();

        $data['sentimentable_id'] = $id;
        $data['sentimentable_type'] = 'post';
        $data['type'] = $sentiment;

        $response = $createOrUpdateService->handle($data);

        return $this->absorb($response)->respond();
    }

    /**
     * @param Request $request
     * @param \App\Services\Sentiments\DeleteService $deleteService
     * @param $id
     * @param $sentiment
     * @param $sentiment_id
     * @return \Illuminate\Http\Response
     */
    public function unsentimentalize(
        Request $request,
        \App\Services\Sentiments\DeleteService $deleteService,
        $id,
        $sentiment,
        $sentiment_id
    ){
        $data = $request->all();

        $data['id'] = $sentiment_id;
        $data['sentimentable_id'] = $id;
        $data['sentimentable_type'] = 'post';
        $data['sentiment_id'] = $sentiment_id;
        $data['type'] = $sentiment;

        $response = $deleteService->handle($data);

        return $this->absorb($response)->respond();
    }

    /**
     * @param Request $request
     * @param UpdateService $updateService
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function update(
        Request $request,
        UpdateService $updateService,
        $id
    ){
        $data = $request->all();

        $data['id'] = $id;

        $response = $updateService->handle($data);

        return $this->absorb($response)->respond();
    }
}
