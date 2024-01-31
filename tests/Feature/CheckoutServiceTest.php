<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Participant;
use App\Services\CartService;
use App\Services\CheckoutService;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;
use Tests\Traits\TestTrait;

class CheckoutServiceTest extends TestCase
{
    use TestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->defaultsetup();

        $this->cart = CartService::createCart($this->product->id);
        $this->cartService = new CartService($this->site, $this->cart);
        $this->cartService->addRmNumbers($this->sortNums);
        $this->cartService->addRmNumbers($this->sortNumQty);
        $this->cart = $this->cart->refresh();

    }

    public function testCheckouCanBeCompleted()
    {


        /** @var Cart $cart */
        $cart = $this->cart;
        $product = $cart->product()->first();
        $freeNumbers = $product->getFreeNumbers();

        $cart->getAllCartNumbers();
        $checkoutService = new CheckoutService($this->site, $this->cart);
        $requestData = $this->getCustomerData();
        $requestData['cart_uuid'] = $cart->uuid;
        $order = $checkoutService->completeCheckout($requestData);
        $this->assertInstanceOf(Order::class, $order);
        /** @var Participant $participant */
        $participant = $order->participant()->first();
        $participantNumbers = $participant->numbers();
        $product = $product->refresh();
        $freeNumbersUpdated = $product->getFreeNumbers();
        foreach ($participantNumbers as $n) {
            $this->assertTrue(in_array($n, $freeNumbers), "order number($n) were not available.");
        }
        foreach ($participantNumbers as $n) {
            $this->assertFalse(in_array($n, $freeNumbersUpdated), "The number($n)  orders are still available.");
        }


    }
}