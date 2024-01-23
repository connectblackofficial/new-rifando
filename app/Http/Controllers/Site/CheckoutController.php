<?php

namespace App\Http\Controllers\Site;


use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
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
            'qtd_zeros'=>$productData['qtd_zeros']
        ];
        return view("site.checkout.index", $pageData);
    }
}