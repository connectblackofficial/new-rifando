<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Cart extends Model
{
    protected $fillable = [
        'product_id', 'participant_id', 'random_numbers', 'uuid', 'numbers', 'total'
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
        $productId = $request->product_id;
        $uuid = $request->uuid;
        return Cart::whereProductId($productId)->whereUuid($uuid)->first();
    }

}
