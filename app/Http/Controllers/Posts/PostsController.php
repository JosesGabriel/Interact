<?php


namespace App\Http\Controllers\Posts;

use App\Http\Controllers\BaseController;
use App\Services\Posts\CreateService;
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
}
