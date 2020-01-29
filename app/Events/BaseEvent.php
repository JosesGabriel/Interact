<?php

namespace App\Events;

/**
 * Class BaseEvent
 *
 * @package App\Events
 */
class BaseEvent
{
    /**
     * @var array|\Illuminate\Http\Request|string
     */
    public $request_user;

    /**
     * BaseEvent constructor.
     */
    public function __construct()
    {
        $this->request_user = array_merge([
            'uuid' => '',
            'username' => 'Lydian User',
            'profile_image' => '',
        ], (request('_user') ?? []));
    }
}
