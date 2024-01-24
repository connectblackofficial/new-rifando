<?php

namespace App\Services;

use App\Enums\CacheKeysEnum;
use App\Enums\ReservationTypeEnum;
use App\Exceptions\UserErrorException;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Ramsey\Uuid\Uuid;

class CartService
{
    private $cartModel;
    private $productResume;
    private $cartNumbers;

    public function __construct(Cart $cartModel)
    {
        $this->cartModel = $cartModel;
        $this->productResume = Product::getResumeCache($cartModel->product_id);
    }

    private function getReservationType($qtyOrNumbers)
    {
        if (is_numeric($qtyOrNumbers)) {
            return ReservationTypeEnum::Automatic;
        } else if (is_array($qtyOrNumbers)) {
            return ReservationTypeEnum::Manual;
        } else {
            throw new UserErrorException("Tipo de reserva inválido.");
        }
    }

    public function addRmNumbers($qtyOrNumbers)
    {
        $productResume = $this->productResume;
        $reservationType = $this->getReservationType($qtyOrNumbers);
        if ($reservationType == ReservationTypeEnum::Automatic) {
            $qtyOrNumbers = intval($qtyOrNumbers);
        } else {
            $qtyOrNumbers = convertToArray($qtyOrNumbers);
        }
        $this->checkProductTypes($reservationType);

        $newQty = $this->getNewQty($reservationType, $qtyOrNumbers);
        $this->checkProductRules($newQty);
        $priceData = $this->getPrice($newQty);
        $price = $priceData['price'];

        if (ReservationTypeEnum::Manual == $reservationType) {
            $allowedNumbers = $productResume['free_numbers'];
            $cartNewItens = [];
            foreach ($qtyOrNumbers as $item) {
                if (!in_array($item, $allowedNumbers)) {
                    throw new UserErrorException("O item '$item' não está disponível.");
                } else {
                    $cartNewItens[$item] = $item;
                }
            }

            $this->cartModel->numbers = json_encode($this->getMergedNewValues($qtyOrNumbers));
        } else {
            $this->cartModel->random_numbers += $qtyOrNumbers;
        }
        $this->cartModel->total = safeMul($price, $newQty);
        $this->cartModel->promo_id = $priceData['promoId'];
        $this->cartModel->saveOrFail();
        return $this->formatCartResponse();
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
        if ($product['type_raffles'] == ReservationTypeEnum::Manual && $reservationType != ReservationTypeEnum::Manual) {
            throw new UserErrorException("Esta rifa só aceita números selecionados automaticamente.");
        } else if ($product['type_raffles'] == ReservationTypeEnum::Automatic && $reservationType != ReservationTypeEnum::Automatic) {
            throw new UserErrorException("Esta rifa só aceita números selecionados automaticamente.");
        }
    }

    private function getNewQty($reservationType, $qtyOrNumbers)
    {
        if ($reservationType == ReservationTypeEnum::Manual) {
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

    private function checkProductRules($newQty)
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
        return $newQty;
    }

    private function getCartNumbers()
    {
        if (is_null($this->cartNumbers)) {
            $this->cartNumbers = $this->cartModel->getNumbersAsArray();
        }
        return $this->cartNumbers;

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
            "qtd_zeros" => $this->productResume['product']['qtd_zeros']
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
}