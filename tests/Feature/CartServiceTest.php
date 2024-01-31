<?php

namespace Tests\Feature;

use App\Enums\RaffleTypeEnum;
use App\Events\ProductUpdated;
use App\Models\Promo;
use App\Services\CartService;
use App\Services\CheckoutService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Traits\TestTrait;

class CartServiceTest extends TestCase
{
    use TestTrait;


    public function setUp(): void
    {
        parent::setUp();
        $this->defaultsetup();

    }

    public function testManualNumbersAddsCorrectAmount()
    {
        $site = $this->site;
        $product = $this->product;

        $sortNums = $this->sortNums;

        $expected = safeMul($product['price'], count($sortNums));
        $cart = $this->cart;
        $cartService = $this->cartService;
        $cartService->addRmNumbers($sortNums);
        $cart = $cart->refresh();
        $this->assertEquals($expected, $cart->total);
        $this->assertEquals(count($sortNums), $cart->getNumbersQty());
    }

    public function testAutoNumbersAddsCorrectAmount()
    {
        $site = $this->site;
        $product = $this->product;
        $currentQty = $this->cart->getNumbersQty();

        $expectedTotal = safeAdd($this->cart->total, safeMul($product['price'], $this->sortNumQty));
        $expectedQty = $currentQty + $this->sortNumQty;
        $cart = $this->cart;
        /** @var CartService $cartService */
        $cartService = $this->cartService;
        $cartService->addRmNumbers($this->sortNumQty);
        $cart = $cart->refresh();
        $this->assertEquals($expectedTotal, $cart->total);
        $this->assertEquals($expectedQty, $cart->getNumbersQty());
    }

    public function testNumbersSubtractsCorrectAmount()
    {

        $cart = $this->cart;

        $this->cartService->addRmNumbers($this->sortNums);
        $cart = $cart->refresh();
        $total = (float)$cart->total;
        $this->cartService->addRmNumbers($this->sortNums);
        $cart = $cart->refresh();
        $expected = safeSub($total, safeMul($this->product->price, count($this->sortNums)));

        $this->assertEquals($expected, $cart->total);
    }

    public function testNumbersWithNegativeValue()
    {
        // Similar setup as the first test
        // ...

        $cart = $this->cart;

        $this->cartService->addRmNumbers($this->sortNumQty);
        $cart->refresh();
        $total = $cart->total;
        $expectedQty = $cart->getNumbersQty() - 1;
        $this->cartService->addRmNumbers(-1);
        $cart = $cart->refresh();
        $expected = safeSub($total, safeMul($this->product->price, 1));
        $this->assertEquals($expected, $cart->total);
        $this->assertEquals($expectedQty, $cart->getNumbersQty());
    }

    public function testCanAddManualAndAutoNumbers()
    {

        $cart = $this->cart;
        $expectedQty = count($this->sortNums) + $this->sortNumQty;
        $expectedPrice = safeMul($expectedQty, $this->product['price']);
        $this->cartService->addRmNumbers($this->sortNums);
        $this->cartService->addRmNumbers($this->sortNumQty);
        $cart->refresh();
        $this->assertEquals($cart->total, $expectedPrice);
        $this->assertEquals($cart->getNumbersQty(), $expectedQty);
    }

    public function testPromoCaBeApplied()
    {
        $this->createCustomPromo();
        $this->product->refresh();
        $productPrice = $this->product->price;

        /** @var Promo $promo */
        foreach ($this->product->promos()->get() as $promo) {
            $cart = $this->cart;
            $cart->clear();
            $cart->refresh();
            $promoQty = $promo->qtdNumeros;

            $cartService = new CartService($this->site, $cart);
            $middleQty = $promoQty / 2;
            $randomNumbers = $this->product->sortNumQty($middleQty);
            $cartService->addRmNumbers($randomNumbers);
            $cartService->addRmNumbers($middleQty);
            $newQty = count($randomNumbers) + $middleQty;
            $priceWithDiscount = safeMul($newQty, safeSub($productPrice, $promo->valor));
            $cart->refresh();

            $this->assertEquals($cart->getNumbersQty(), $newQty);

            $this->assertEquals($cart->total, $priceWithDiscount);
            $this->assertEquals($cart->promo_id, $promo->id);
            $cartService->addRmNumbers($randomNumbers);
            $cart->refresh();
            $this->assertGreaterThan($cart->total, $priceWithDiscount);
            $this->assertNotEquals($cart->promo_id, $promo->id);


        }

        /*$expectedQty = count($this->sortNums) + $this->sortNumQty;
        $expectedPrice = safeMul($expectedQty, $this->product['price']);
        $this->cartService->addRmNumbers($this->sortNums);
        $this->cartService->addRmNumbers($this->sortNumQty);
        $cart->refresh();
        $this->assertEquals($cart->total, $expectedPrice);
        $this->assertEquals($cart->getNumbersQty(), $expectedQty);*/
    }

    public function testProductCanBeRefreshed()
    {
        $this->cartService->addRmNumbers($this->sortNumQty);
        $oldCart = $this->cart->refresh();
        $cols = ['total', 'uuid', 'product_id', 'random_numbers', 'numbers'];
        $newCart = CartService::refresh($this->site, $oldCart);
        $this->assertNotNull($newCart->id);
        foreach ($cols as $c) {
            $this->assertEquals($oldCart[$c], $newCart[$c]);
        }
    }


}
