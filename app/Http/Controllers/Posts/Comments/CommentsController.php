<?php


namespace App\Http\Controllers\Posts\Comments;

use App\Http\Controllers\BaseController;
use App\Services\Posts\Comments\CreateService;
use App\Services\Posts\Comments\DeleteService;
use Illuminate\Http\Request;

/**
 * Class CommentsController
 *
 * @package App\Http\Controllers\Posts\Comments
 */
class CommentsController extends BaseController
{
    /**
     * @param Request $request
     * @param CreateService $createService
     * @param $post_id
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, CreateService $createService, $post_id)
    {
        $data = $request->all();

        $data['post_id'] = $post_id;

        $response = $createService->handle($data);

        return $this->absorb($response)->respond();
    }

    /**
     * @param Request $request
     * @param DeleteService $deleteService
     * @param $post_id
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function delete(
        Request $request,
        DeleteService $deleteService,
        $post_id,
        $id
    ){
        $data = $request->all();

        $data['id'] = $id;
        $data['post_id'] = $post_id;

        $response = $deleteService->handle($data);

        return $this->absorb($response)->respond();
    }
}
