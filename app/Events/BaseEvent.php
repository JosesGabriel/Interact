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
        $this->request_user = request('_user') ?? [ 'username' => 'Lydian User'];
    }
}
