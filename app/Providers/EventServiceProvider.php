<?php

namespace App\Providers;

use App\Events\ProductCreated;
use App\Events\ProductUpdated;
use App\Listeners\CheckoutCompletedEventListener;
use App\Listeners\ProductCreatedListener;
use App\Listeners\ProductUpdatedListener;
use CheckoutCompletedListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
        ProductUpdated::class => [
            ProductUpdatedListener::class,
        ],
        ProductCreated::class => [
            ProductCreatedListener::class,
        ],
        CheckoutCompletedListener::class => [
            CheckoutCompletedEventListener::class,
        ]
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
