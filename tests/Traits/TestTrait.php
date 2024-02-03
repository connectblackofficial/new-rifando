<?php

namespace Tests\Traits;

use App\Enums\GameModeEnum;
use App\Enums\PaymentGatewayEnum;
use App\Enums\RaffleTypeEnum;
use App\Events\ProductUpdated;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Participant;
use App\Models\Product;
use App\Models\Promo;
use App\Models\Site;
use App\Models\User;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\ProductService;
use Faker\Provider\en_IN\Person;
use Illuminate\Http\UploadedFile;
use Faker\Generator as Faker;

trait TestTrait
{

    private $site;
    private $product;
    private $cartService;
    private $cart;
    private $sortNums;

    private $sortNumQty = 3;

    /** @var Faker $faker */
    private $faker;

    public function getUser()
    {
        return User::first();
    }

    public function getSiteConfig()
    {
        return Site::where('user_id', $this->getUser()->id)->first();
    }

    public function setSiteConfig()
    {
        setSiteEnv($this->getSiteConfig());
    }

    public function getRandomProductData()
    {

        $name = "Rifa do site " . $this->faker->domainName;
        return [
            'name' => $name,
            'subname' => $name,
            'slug' => createSlug($name) . rand(1111, 99999),
            'price' => 1000,
            'gateway' => PaymentGatewayEnum::MP,
            'modo_de_jogo' => GameModeEnum::Numbers,
            'numbers' => 999,
            'description' => 'Descrição do produto teste',
            'minimo' => 1,
            'maximo' => 100,
            'expiracao' => 3600,
            'images' => $this->getRandomImages(),
            'type_raffles' => RaffleTypeEnum::Merged
        ];
    }

    public function getSmallDecimal()
    {
        return safeDiv(rand(1, 99), 100);
    }

    public function getRandomImages()
    {
        $list = [1, 2, 3];
        shuffle($list);
        $images = [];
        for ($i = 1; $i <= $list[0]; $i++) {
            $images[] = UploadedFile::fake()->image('imagem' . $i . '.jpg');
        }
        return $images;

    }

    public function getRandomProduct()
    {
        $service = new ProductService($this->getSiteConfig());
        $productData = $this->getRandomProductData();
        return $service->processAddProduct($productData, $productData['images']);

    }

    public function createCustomPromo()
    {
        Promo::where("product_id", $this->product->id)->delete();
        $price = $this->product['price'];
        $base = 10;
        for ($i = 1; $i <= 4; $i++) {
            $intDiscount = $base * $i;
            $percent = safeDiv($intDiscount, 100);
            Promo::create([
                'qtdNumeros' => $i * 2,
                'ordem' => $i,
                'desconto' => $intDiscount,
                'valor' => safeMul($percent, $price),
                'user_id' => $this->product['user_id'],
                'product_id' => $this->product->id
            ]);
        }
        $product = $this->product->refresh();
        event(new ProductUpdated($product));
    }

    public function cartRandomAdd($afterAddCallBack, $afterRemoveCallback, $autoQty = 0, $numsQty = 0)
    {

        $cartService = new CartService($this->site, $this->cart);
        $randomNumbers = [];
        if ($numsQty > 0 && $this->product->canAddManualNum()) {
            $randomNumbers = $this->product->sortNumQty($numsQty);
            $cartService->addRmNumbers($randomNumbers);
        }
        if ($autoQty <> 0 && $this->product->canAddAutoNum()) {
            $cartService->addRmNumbers($autoQty);
        }
        $expectedQty = count($randomNumbers) + $autoQty;
        $cart = $this->cart->refresh();
        $this->assertEquals($expectedQty, $cart->getNumbersQty());
        $afterAddCallBack($cart);
        $totalAfterAdd = $cart->total;
        $qtyAfterAdd = $cart->getNumbersQty();

        if ($numsQty > 0) {
            $cartService->addRmNumbers($randomNumbers);
        }
        for ($i = 1; $i <= $cart->random_numbers; $i++) {
            $cartService->addRmNumbers(-1);
        }
        $cart = $this->cart->refresh();

        $this->assertEquals(0, $cart->getNumbersQty());
        $this->assertEquals(0, $cart->total);


    }

    public function cartT(int $autoQty, array $manualNums, int $expectedQty, float $expectedTotal): Cart
    {
        if ($autoQty <> 0) {
            $this->cartService->addRmNumbers($autoQty);
        }
        if (count($manualNums) > 0) {
            $this->cartService->addRmNumbers($manualNums);

        }
        $cart = $this->cart->refresh();
        $this->assertEquals($expectedQty, $cart->getNumbersQty());
        $this->assertEquals($expectedTotal, $cart->total);
        return $cart;

    }

    public function adRmToCart(array $manualNums, $sortNums, $expectedQty = false, $price = null)
    {
        if (is_null($price)) {
            $price = $this->product->price;
        }
        $qty = 0;
        if ($this->product->canAddManualNum() && count($manualNums) > 0) {
            $this->cartService->addRmNumbers($manualNums);
            $qty += count($manualNums);
        }
        if ($this->product->canAddManualNum() && $sortNums > 0) {
            $this->cartService->addRmNumbers($manualNums);
            $qty += $sortNums;
        }
        if (!$expectedQty) {
            $expectedQty = $qty;
        }
        $expectedPrice = safeMul($expectedQty, $price);
        $this->cart->refresh();
        $this->assertEquals($this->cart->total, $expectedPrice);
        $this->assertEquals($this->cart->getNumbersQty(), $expectedQty);
        return $this->cart->refresh();
    }

    public function basicSetup()
    {
        // Configuração do site
        $this->setSiteConfig();
        $this->site = $this->getSiteConfig();
        $this->faker = \Faker\Factory::create();
    }

    public function defaultsetup()
    {
        $this->basicSetup();

        // Criação e configuração do produto
        $this->product = $this->getRandomProduct();
        $this->product->type_raffles = RaffleTypeEnum::Merged;
        $this->product->saveOrFail();

        // Criação do carrinho e instância do CartService
        $this->cart = CartService::createCart($this->product->id);
        $this->cartService = new CartService($this->site, $this->cart);

        $this->sortNums = $this->product->sortNumQty(4);

    }

    public function getCustomerData()
    {
        $name = $this->faker->name;
        return [
            'ddi' => "+55",
            "phone" => "7599242" . rand(1111, 9999),
            "email" => $this->faker->email,
            'cpf' => "07559659578",
            'name' => $name,
            'nome' => $name
        ];
    }

    public function checkoutCanBeCompleted($gateway = null)
    {
        /** @var Cart $cart */
        $cart = $this->cart;
        $product = $cart->product()->first();
        if (!is_null($gateway)) {
            $product->gateway = $gateway;
            $product->saveOrFail();
            event(new ProductUpdated($product));
        }
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