<?php

namespace App\Listeners;

use App\Events\ProductCreated;
use App\Models\Product;
use App\Services\ProductService;

class ProductCreatedListener
{
    public function __construct()
    {
    }

    public function handle(ProductCreated $event): void
    {
        $product = $event->product;
        Product::getResumeCache($product['id'], true);

        $product = $event->product;
        ProductService::processRafflePages($product);

    }
}
