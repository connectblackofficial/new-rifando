<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Site;

class CheckoutService
{

    private $cartModel;
    private $productResume;

    public function __construct(Cart $cartModel)
    {
        $this->cartModel = $cartModel;
        $this->productResume = Product::getResumeCache($cartModel->product_id);
    }

    public function completeCheckout(array $requestData)
    {
        if (isset($requestData['customer_id']) && !is_null($requestData['customer_id'])) {
            $customer = Customer::whereId($requestData['customer_id'])->where("user_id", $config->user_id)->firstOrFail();

        }


    }

}