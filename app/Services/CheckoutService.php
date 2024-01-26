<?php

namespace App\Services;

use App\Exceptions\UserErrorException;
use App\Models\AffiliateRaffle;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Site;
use App\Rules\CpfValidation;

class CheckoutService
{

    private $cartModel;
    private $productResume;
    private $siteConfig;

    public function __construct(Site $siteConfig, Cart $cartModel)
    {
        $this->siteConfig = $siteConfig;
        $this->cartModel = $cartModel;
        $this->productResume = Product::getResumeCache($cartModel->product_id);
    }

    public function completeCheckout(array $requestData)
    {
        $siteOwner = $this->siteConfig['user_id'];
        $siteConfig = $this->siteConfig;
        $customerService = new CustomerService($siteConfig);
        $customer = $customerService->createOrGet($requestData);
        $cart = Cart::whereUuid($requestData['cart_uuid'])->first();
        if (!isset($cart['id'])) {
            throw new UserErrorException("Checkout inválido.");
        }
        $product = $cart->product()->first();
        if (!isset($product['id']) || $product['user_id'] != $siteOwner) {
            throw UserErrorException::productNotFound();
        }

        $cart = CartService::refresh($siteConfig, $cart);


        $productResume = $this->productResume;


        $disponiveis = $productResume['free_numbers'];
        $numbers = array_merge($this->sortAutoNumbers(), array_values($cart->getNumbersAsArray()));

    }

    public function sortAutoNumbers()
    {
        $randomNumbers = $this->cartModel->random_numbers;
        if ($randomNumbers <= 0) {
            return [];
        }
        $limit = ($randomNumbers - 1);
        $freeNumbers = $this->productResume['free_numbers'];
        shuffle($freeNumbers);
        return array_slice($freeNumbers, 0, $limit);

    }


}