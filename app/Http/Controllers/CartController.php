<?php

namespace App\Http\Controllers;

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
            return (new CartService($cartModel))->addRmNumbers($request->qty_or_list);
        };
        return $this->processAjaxResponse($request->all(), $rules, $callback);

    }

    public function index(Request $request)
    {
        $rules = $this->getBasicRules();
        $callback = function () use ($request) {
            $cartModel = Cart::getCartFromRequest($request);
            return (new CartService($cartModel))->formatCartResponse();
        };
        return $this->processAjaxResponse($request->all(), $rules, $callback);
    }

    private function getBasicRules()
    {
        return [
            'product_id' => 'required|integer',
            'uuid' => 'required'
        ];
    }

    public function destroy(Request $request)
    {
        $rules = $this->getBasicRules();
        $callback = function () use ($request) {
            $cartModel = Cart::getCartFromRequest($request);
            $newCartModel = CartService::resetCart($cartModel);
            return (new CartService($newCartModel))->formatCartResponse();
        };
        return $this->processAjaxResponse($request->all(), $rules, $callback);
    }
}
