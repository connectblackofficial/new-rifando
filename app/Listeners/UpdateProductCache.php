<?php

namespace App\Listeners;

use App\Events\ProductUpdated;
use App\Models\Product;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateProductCache
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ProductUpdated $event
     * @return void
     */
    public function handle(ProductUpdated $event)
    {
        $product = $event->product;
        Product::getResumeCache($product['id'], true);
    }
}
