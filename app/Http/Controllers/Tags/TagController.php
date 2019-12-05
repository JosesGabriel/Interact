<?php

namespace App\Http\Controllers\Tags;

use App\Http\Controllers\BaseController;
use App\Services\Tags\CreateService;
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
}
