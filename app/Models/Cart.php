<?php

namespace App\Models;

use App\Traits\ModelSiteOwnerTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Cart extends Model
{
    use ModelSiteOwnerTrait;

    protected $fillable = [
        'product_id', 'participant_id', 'random_numbers', 'uuid', 'numbers', 'total', 'promo_id'
    ];

    public function getNumbersQty()
    {
        return count($this->getNumbersAsArray()) + $this->random_numbers;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getNumbersAsArray()
    {
        if (is_null($this->numbers)) {
            return [];
        }
        return json_decode($this->numbers, true);
    }

    public static function getCartFromRequest(Request $request)
    {
        $productUuid = $request->product_uuid;
        $uuid = $request->cart_uuid;
        $product = Product::where("uuid", $productUuid)->select("id")->firstOrFail();
        return Cart::whereProductId($product['id'])->whereUuid($uuid)->first();
    }

}
