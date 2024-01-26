<?php

namespace App\Listeners;

use App\Events\ProductUpdated;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProductUpdatedListener
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
        $product=$event->product->refresh();
        Product::getResumeCache($product['id'], true);
        ProductService::processRafflePages($product);
    }
}
