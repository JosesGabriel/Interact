<?php


namespace App\Http\Controllers\Posts\Comments;

use App\Http\Controllers\BaseController;
use App\Services\Posts\Comments\CreateService;
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
}
