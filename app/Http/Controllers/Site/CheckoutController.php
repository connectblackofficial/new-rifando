<?php

namespace App\Http\Controllers\Site;


use App\Http\Controllers\Controller;
use App\Http\Requests\CompleteCheckoutRequest;
use App\Http\Requests\PhoneRequest;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Participant;
use App\Models\PrizeDraw;
use App\Models\Product;
use App\Models\Raffle;
use App\Services\CheckoutService;
use Illuminate\Http\Request;


class CheckoutController extends Controller
{
    public function index($cartUuid, Request $request)
    {
        $cart = Cart::whereUuid($cartUuid)->firstOrFail();
        $productResume = Product::getResumeCache($cart->product_id);
        $productData = $productResume['product'];
        checkUserIdSite($productData['user_id']);

        $pageData = [
            'tokenAfiliado' => $request->tokenAfiliado,
            'product' => $productResume['product'],
            'cart' => $cart,
            'numbers' => $cart->getNumbersAsArray(),
            'qtd_zeros' => $productData['qtd_zeros'],
            'config' => getSiteConfig()
        ];
        return view("site.checkout.index", $pageData);
    }

    public function step1(Request $request)
    {
        $rules = [
            'cart_uuid' => 'required',
            'phone' => 'required|min:8|max:15'
        ];
    }

    public function completeCheckout(Request $request)
    {

        $rules = (new CompleteCheckoutRequest())->rules();
        $action = function () use ($request) {
            $customer = Customer::siteOwner()->phoneFromRequest($request)->first();
            $response['customer'] = $customer;
            return $response;
        };
        return $this->processAjaxResponse(['phone' => $request->phone, 'ddi' => $request->ddi], $rules, $action, true);
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