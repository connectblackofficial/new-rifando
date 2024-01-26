<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Rules\ArrayOrIntRule;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function addRm(Request $request)
    {
        $rules = $this->getBasicRules();
        $rules['qty_or_list'] = ['required', new ArrayOrIntRule()];
        $callback = function () use ($request) {
            $cartModel = Cart::getCartFromRequest($request);
            return (new CartService(getSiteConfig(), $cartModel))->addRmNumbers($request->qty_or_list);
        };
        return $this->processAjaxResponse($request->all(), $rules, $callback);

    }

    public function index(Request $request)
    {
        $rules = $this->getBasicRules();
        $callback = function () use ($request) {
            $cartModel = Cart::getCartFromRequest($request);
            return (new CartService(getSiteConfig(), $cartModel))->formatCartResponse();
        };
        return $this->processAjaxResponse($request->all(), $rules, $callback);
    }

    private function getBasicRules()
    {
        return [
            'product_uuid' => config("constants.product_uuid_rule"),
            'cart_uuid' => config("constants.cart_uuid")
        ];
    }

    public function destroy(Request $request)
    {
        $rules = $this->getBasicRules();
        $callback = function () use ($request) {
            $cartModel = Cart::getCartFromRequest($request);
            $newCartModel = CartService::resetCart($cartModel);
            return (new CartService(getSiteConfig(), $newCartModel))->formatCartResponse();
        };
        return $this->processAjaxResponse($request->all(), $rules, $callback);
    }
}
