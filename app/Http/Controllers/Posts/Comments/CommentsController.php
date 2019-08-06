<?php


namespace App\Http\Controllers\Posts\Comments;

use App\Http\Controllers\BaseController;
use App\Services\Posts\Comments\AddAttachmentService;
use App\Services\Posts\Comments\CreateService;
use App\Services\Posts\Comments\DeleteService;
use App\Services\Posts\Comments\SearchService;
use App\Services\Posts\Comments\UpdateService;
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
     * @param AddAttachmentService $addAttachmentService
     * @param $post_id
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function addAttachment(
        Request $request,
        AddAttachmentService $addAttachmentService,
        $post_id,
        $id
    ){
        $data = $request->all();

        $data['attachable_id'] = $id;
        $data['attachable_type'] = 'comment';
        $data['id'] = $id;
        $data['post_id'] = $post_id;

        $response = $addAttachmentService->handle($data);

        return $this->absorb($response)->respond();
    }

    /**
     * Fetches a post's comments with subcomments
     *
     * @param Request $request
     * @param SearchService $searchService
     * @param $post_id
     * @return \Illuminate\Http\Response
     */
    public function all(
        Request $request,
        SearchService $searchService,
        $post_id
    ){
        $data = $request->all();

        $data['post_id'] = $post_id;
        $data['parent_id'] = 0;
        $data['with'] = ['comments'];

        $response = $searchService->handle($data);

        return $this->absorb($response)->respond();
    }

    /**
     * @param Request $request
     * @param CreateService $createService
     * @param $post_id
     * @return \Illuminate\Http\Response
     */
    public function create(
        Request $request,
        CreateService $createService,
        $post_id
    ){
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

    /**
     * @param Request $request
     * @param UpdateService $updateService
     * @param $post_id
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function update(
        Request $request,
        UpdateService $updateService,
        $post_id,
        $id
    ){
        $data = $request->all();

        $data['id'] = $id;
        $data['post_id'] = $post_id;

        $response = $updateService->handle($data);

        return $this->absorb($response)->respond();
    }
}
