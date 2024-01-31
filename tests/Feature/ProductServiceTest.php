<?php

namespace Tests\Feature;

use App\Enums\GameModeEnum;
use App\Enums\RaffleTypeEnum;
use App\Events\ProductCreated;
use App\Models\Product;
use App\Services\ProductService;
use Tests\TestCase;
use Tests\Traits\TestTrait;

class ProductServiceTest extends TestCase
{
    use TestTrait;
    public function setUp(): void
    {
        parent::setUp();
        $this->basicSetup();

    }
    public function testProductCanBeCreated()
    {
        $this->setSiteConfig();

        $siteConfig = $this->getSiteConfig();
        $service = new ProductService($siteConfig);
        $productData = $this->getRandomProductData();
        $productData['modo_de_jogo'] = GameModeEnum::Numbers;

        $product = $service->processAddProduct($productData, $productData['images']);
        $this->assertInstanceOf(Product::class, $product);
        $this->assertTrue($product->hasImages());
        $this->assertNotEmpty($product->descricao());
        $this->assertGreaterThanOrEqual(1, $product->promos()->count());
        $this->assertGreaterThanOrEqual(1, count($product->prizeDraws()));
        $this->assertGreaterThanOrEqual(1, count($product->numbers()));
        $this->assertGreaterThanOrEqual(1, $product->shoppingSuggestions()->count());
        $this->assertGreaterThanOrEqual(1, $product->descriptions()->count());

    }


    public function testCanBeUpdated()
    {
        $this->setSiteConfig();

        $siteConfig = $this->getSiteConfig();
        $product = $this->getRandomProduct();
        $product = Product::findOrFail($product['id']);
        $requestData = Product::withInputsNames($product);
        $service = new ProductService($siteConfig);
        $product = $service->update($product, $requestData);
        $this->assertInstanceOf(Product::class, $product);

    }


}
