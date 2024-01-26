<?php

namespace App\Services;

use App\Enums\CacheKeysEnum;
use App\Enums\RaffleTypeEnum;
use App\Exceptions\UserErrorException;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Site;
use Illuminate\Support\Facades\Session;
use Ramsey\Uuid\Uuid;

class CartService
{
    private $cartModel;
    private $productResume;
    private $cartNumbers;
    private $siteConfig;

    public function __construct(Site $siteConfig, Cart $cartModel)
    {
        $this->siteConfig = $siteConfig;
        $this->cartModel = $cartModel;
        $this->productResume = Product::getResumeCache($cartModel->product_id);
    }

    private function getReservationType($qtyOrNumbers)
    {
        if (is_numeric($qtyOrNumbers)) {
            return RaffleTypeEnum::Automatic;
        } else if (is_array($qtyOrNumbers)) {
            return RaffleTypeEnum::Manual;
        } else {
            throw new UserErrorException("Tipo de reserva inválido.");
        }
    }

    public function addRmNumbers($qtyOrNumbers)
    {
        $reservationType = $this->getReservationType($qtyOrNumbers);
        if ($reservationType == RaffleTypeEnum::Automatic) {
            $qtyOrNumbers = intval($qtyOrNumbers);
        } else {
            $qtyOrNumbers = convertToArray($qtyOrNumbers);
        }
        $this->checkProductTypes($reservationType);

        $newQty = $this->getNewQty($reservationType, $qtyOrNumbers);
        $this->checkProductRules($newQty);
        $priceData = $this->getPrice($newQty);
        $price = $priceData['price'];

        if (RaffleTypeEnum::Manual == $reservationType) {
            $qtyOrNumbers = $this->checkItens($qtyOrNumbers);
            $this->cartModel->numbers = json_encode($this->getMergedNewValues($qtyOrNumbers));
        } else {
            $this->cartModel->random_numbers += $qtyOrNumbers;
        }
        $this->cartModel->total = safeMul($price, $newQty);
        $this->cartModel->promo_id = $priceData['promoId'];
        $this->cartModel->saveOrFail();
        return $this->formatCartResponse();
    }

    public function checkItens($qtyOrNumbers)
    {

        $allowedNumbers = $this->productResume['free_numbers'];
        $cartNewItens = [];
        foreach ($qtyOrNumbers as $item) {
            if (!in_array($item, $allowedNumbers,true)) {
                throw new UserErrorException("O item '$item' não está disponível.");
            } else {
                $cartNewItens[$item] = $item;
            }
        }
        return $cartNewItens;
    }

    private function getPrice($newQty)
    {
        $product = $this->productResume['product'];
        $productResume = $this->productResume;
        $price = $product['price'];
        $discount = 0;
        $promoId = null;
        foreach ($productResume['promos'] as $promo) {
            if ($newQty >= $promo['qtdNumeros'] && $promo['valor'] > 0) {
                $discount = $promo['valor'];
                $promoId = $promo['id'];
            }
        }
        $newPrice = $price - $discount;
        if (0 > $newPrice) {
            throw new UserErrorException("O valor da rifa não pode ser negativo.");
        }

        return ['price' => ($price - $discount), 'promoId' => $promoId];

    }

    private function checkProductTypes($reservationType)
    {
        $product = $this->productResume['product'];
        if ($product['type_raffles'] == RaffleTypeEnum::Manual && $reservationType != RaffleTypeEnum::Manual) {
            throw new UserErrorException("Esta rifa só aceita números selecionados automaticamente.");
        } else if ($product['type_raffles'] == RaffleTypeEnum::Automatic && $reservationType != RaffleTypeEnum::Automatic) {
            throw new UserErrorException("Esta rifa só aceita números selecionados automaticamente.");
        }
    }

    private function getNewQty($reservationType, $qtyOrNumbers)
    {
        if ($reservationType == RaffleTypeEnum::Manual) {
            $numbers = $this->getMergedNewValues($qtyOrNumbers);
            return count($numbers) + $this->cartModel->random_numbers;
        } else {
            $newRandom = $this->cartModel->random_numbers + $qtyOrNumbers;
            return count($this->getCartNumbers()) + $newRandom;

        }

    }

    private function getMergedNewValues($qtyOrNumbers)
    {
        $cartsNumbers = $this->getCartNumbers();

        foreach ($qtyOrNumbers as $number) {
            if (isset($cartsNumbers[$number])) {
                unset($cartsNumbers[$number]);
            } else {
                $cartsNumbers[$number] = $number;
            }
        }

        return $cartsNumbers;
    }

    public function checkProductRules($newQty)
    {
        $product = $this->productResume['product'];
        $product['minimo'] = 0;
        if ($product['status'] != "Ativo") {
            throw new UserErrorException("Essa rifa não está mais ativa.");
        }
        if ($newQty > $product['maximo']) {
            throw new UserErrorException("A quantidade de números não pode ser superior a {$product['maximo']}.");
        }
        if ($newQty < $product['minimo']) {
            throw new UserErrorException("A quantidade de números não pode ser menor que {$product['minimo']}.");
        }
        if ($newQty > $this->productResume['free']) {
            throw new UserErrorException("A quantidade de números não pode ser maior que a oferta disponível.");
        }
        if ($newQty >= 10000) {
            throw new UserErrorException("Você só pode comprar no máximo 10.000 números por vez.");
        }
        return $newQty;
    }

    private function getCartNumbers()
    {
        return $this->cartModel->getNumbersAsArray();


    }

    public function formatCartResponse()
    {
        $cartData = [
            'uuid' => $this->cartModel->uuid,
            'id' => $this->cartModel->id,
            'total' => $this->cartModel->total,
            'formated_total' => formatMoney($this->cartModel->total, false),
            'qty_numbers' => $this->cartModel->getNumbersQty(),
            'random_numbers' => $this->cartModel->random_numbers,
            'numbers' => $this->cartModel->getNumbersAsArray(),
            "qtd_zeros" => $this->productResume['product']['qtd_zeros'],
            'game_mode'=>$this->productResume['product']['modo_de_jogo']
        ];
        $cartData['view'] = view("site.cart.index", $cartData)->render();
        return $cartData;

    }

    static function currentCart($productId): Cart
    {
        $cartKey = CacheKeysEnum::getCartSessionKey($productId);
        if (Session::exists($cartKey)) {
            $cart = Cart::where("uuid", Session::get($cartKey))->first();
            if (is_null($cart)) {
                return self::createCart($productId);
            }
            return $cart;
        } else {
            return self::createCart($productId);


        }

    }

    public static function createCart($productId)
    {
        $cartKey = CacheKeysEnum::getCartSessionKey($productId);
        $cart = new Cart();
        $cart->product_id = $productId;
        $cart->uuid = Uuid::uuid4();
        $cart->saveOrFail();
        Session::put($cartKey, $cart->uuid);
        return $cart;
    }

    public static function resetCart($cartModel)
    {
        $cart = $cartModel;
        $productId = $cartModel->product_id;
        $cartKey = CacheKeysEnum::getCartSessionKey($productId);
        Session::forget($cartKey);
        $cart->delete();
        return self::currentCart($productId);
    }

    public static function refresh(Site $site, Cart $oldCart): Cart
    {
        $oldCartUuid = $oldCart->uuid;
        $oldCartId = $oldCart->id;

        $numbersQty = $oldCart->random_numbers;
        $manualNumbers = [];
        $newCartUuid = Uuid::uuid4();

        $newCart = new Cart();
        $newCart->product_id = $oldCart->product_id;
        $newCart->uuid = $newCartUuid;
        $newCart->saveOrFail();

        $cartService = new CartService($site, $newCart);
        foreach ($oldCart->getNumbersAsArray() as $n) {
            $manualNumbers[] = $n;
        }
        if (count($manualNumbers) > 0) {
            $cartService->addRmNumbers($manualNumbers);
        }
        if ($numbersQty > 0) {
            $cartService->addRmNumbers($numbersQty);
        }
        $newCart = Cart::whereUuid($newCartUuid)->first();
        if (isset($newCart['id'])) {
            $oldCart->delete();
            $newCart->id = $oldCartId;
            $newCart->uuid = $oldCartUuid;
            $newCart->saveOrFail();
        }


        return $newCart;
    }


}