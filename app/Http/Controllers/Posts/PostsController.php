<?php


namespace App\Http\Controllers\Posts;

use App\Http\Controllers\BaseController;
use App\Services\Posts\CreateService;
use App\Services\Posts\DeleteService;
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
}
