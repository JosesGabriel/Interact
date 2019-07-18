<?php


namespace App\Http\Controllers\Posts;

use App\Http\Controllers\BaseController;
use App\Services\Posts\CreateService;
use App\Services\Posts\DeleteService;
use App\Services\Posts\FetchService;
use App\Services\Posts\UpdateService;
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
     * @param CreateService $create_service
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, CreateService $create_service)
    {
        $data = $request->all();

        $response = $create_service->handle($data);

        return $this->absorb($response)->respond();
    }

    /**
     * @param Request $request
     * @param DeleteService $deleteService
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, DeleteService $deleteService)
    {
        $data = $request->all();

        $response = $deleteService->handle($data);

        return $this->absorb($response)->respond();
    }

    /**
     * Fetch a post by id
     *
     * @param Request $request
     * @param FetchService $fetchService
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request, FetchService $fetchService)
    {
        $data = $request->all();

        $response = $fetchService->handle($data);

        return $this->absorb($response)->respond();
    }

    /**
     * @param Request $request
     * @param UpdateService $updateService
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UpdateService $updateService)
    {
        $data = $request->all();

        $response = $updateService->handle($data);

        return $this->absorb($response)->respond();
    }
}
