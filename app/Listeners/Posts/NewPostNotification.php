<?php

namespace App\Listeners\Posts;

use App\Events\Posts\UserPostedEvent;
use App\Jobs\SendWebNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Class NewPostNotification
 *
 * @package App\Listeners\Posts
 */
class NewPostNotification implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * @var SendWebNotification
     */
    private $sendWebNotification;

    /**
     * Create the event listener.
     *
     * @param SendWebNotification $sendWebNotification
     */
    public function __construct(SendWebNotification $sendWebNotification)
    {
        $this->sendWebNotification = $sendWebNotification;
    }

    /**
     * Handle the event.
     *
     * @param  UserPostedEvent  $event
     * @return void
     */
    public function handle(UserPostedEvent $event)
    {
        $this->sendWebNotification::dispatch([
            'message' => 'There is a new post.',
        ], 'social.post');
    }

    /**
     * Handle a job failure.
     *
     * @param UserPostedEvent $event
     * @param \Exception $exception
     * @return void
     */
    public function failed(UserPostedEvent $event, $exception)
    {

    }
}
