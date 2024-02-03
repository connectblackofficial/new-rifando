<?php

namespace Tests\Feature;

use App\Enums\PaymentGatewayEnum;
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
        $this->checkoutCanBeCompleted();

    }

    public function testCheckouCanBeCompletedWithMp()
    {
        $this->checkoutCanBeCompleted(PaymentGatewayEnum::MP);

    }

    public function testCheckouCanBeCompletedWithAsaas()
    {
        $this->checkoutCanBeCompleted(PaymentGatewayEnum::ASAAS);

    }
}