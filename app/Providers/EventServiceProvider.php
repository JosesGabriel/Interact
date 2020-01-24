<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\Posts\UserPostedEvent::class => [
            \App\Listeners\Posts\NewPostNotification::class,
            \App\Listeners\Posts\MentionNotification::class,
        ],
        \App\Events\Posts\Comments\UserCommentedEvent::class => [
            \App\Listeners\Posts\Comments\NewCommentNotification::class,
            \App\Listeners\Posts\Comments\ParentAuthorNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
