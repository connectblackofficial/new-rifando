<?php

namespace App\Http\Controllers\Site;


use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CompleteCheckoutRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Services\CheckoutService;
use Illuminate\Http\Request;


class CheckoutController extends Controller
{
    public function index($cartUuid, Request $request)
    {
        $action = function () use ($request, $cartUuid) {
            $cartData = Cart::getCartByUuidOrFail($cartUuid);
            $cart = $cartData['cart'];
            $productResume = $cartData['productResume'];
            $productData = $productResume['product'];
            $pageData = [
                'tokenAfiliado' => $request->tokenAfiliado,
                'product' => $productResume['product'],
                'cart' => $cart,
                'numbers' => $cart->getNumbersAsArray(),
                'qtd_zeros' => $productData['qtd_zeros'],
                'config' => getSiteConfig()
            ];
            return ['html' => view("site.checkout.complete", $pageData)->render()];
        };

        return $this->processAjaxResponse([], [], $action);
    }

    public function completeCheckout(Request $request)
    {
        $site = getSiteConfig();
        $rules = (new CompleteCheckoutRequest())->rules();
        $postData = $request->all();

        if (isset($postData['DDI'])) {
            $postData['ddi'] = $postData['DDI'];
        }


        $action = function () use ($postData, $site) {
            $cartData = Cart::getCartByUuidOrFail($postData['cart_uuid']);
            $cart = $cartData['cart'];
            $checkoutService = new CheckoutService($site, $cart);
            /*** @var Order $order * */
            $order = $checkoutService->completeCheckout($postData);
            return [
                'redirect_url' => route("site.checkout.pay", ['uuid' => $order->uuid])
            ];
        };
        return $this->processAjaxResponse($postData, $rules, $action);

    }

    public function payment($orderuuid, Request $request)
    {

        $page = function () use ($orderuuid) {
            $site = getSiteConfig();
            $order = Order::siteOwner()->whereUuid($orderuuid)->first();
            return CheckoutService::paymentPage($site, $order);
        };


        return $this->catchJsonResponse($page);
    }

}