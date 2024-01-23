<?php

namespace Tests\Feature;

use App\Events\ProductCreated;
use App\Models\Product;
use App\Services\ProductService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Traits\TestTrait;

class ProductServiceTest extends TestCase
{
    use TestTrait;

    public function testProductCanBeCreated()
    {
        \Event::fake();
        $this->setSiteConfig();
        $service = new ProductService();
        $productData = $this->getRandomProductData();
        $product = $service->processAddProduct($productData, $productData['images']);
        $this->assertInstanceOf(Product::class, $product);
        $this->assertTrue($product->hasImages());
        $this->assertNotEmpty($product->descricao());
        $this->assertGreaterThanOrEqual(1, $product->promos()->count());
        $this->assertGreaterThanOrEqual(1, count($product->premios()));
        $this->assertGreaterThanOrEqual(1, count($product->numbers()));
        \Event::assertDispatched(ProductCreated::class);
    }

    public function testCanBeUpdated()
    {
        $this->setSiteConfig();
        $product = $this->getRandomProduct();
        $product=Product::findOrFail($product['id']);
        $requestData = convertToArray($product);


        $service = new ProductService();
        $requestData['favoritar_rifa'] = $product->favoritar;
        $requestData['cadastrar_ganhador']=$product->winner;
        $product = $service->update($product, $requestData);
        $this->assertInstanceOf(Product::class, $product);

    }
}
