<?php

namespace App\Models;

use App\Traits\ModelSiteOwnerTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Cart extends Model
{
    use ModelSiteOwnerTrait;

    protected $fillable = [
        'id', 'product_id', 'participant_id', 'random_numbers', 'uuid', 'numbers', 'total', 'promo_id'
    ];

    protected $casts = [
        'total' => 'float',
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

    public function clear()
    {
        $this->total = 0;
        $this->random_numbers = 0;
        $this->numbers = null;
        $this->promo_id = null;
        $this->participant_id = null;
        $this->saveOrFail();
        $this->refresh();
    }

    public function alreadyOnCart()
    {

    }

    public function sortAutoNumbers()
    {
        $productResume = Product::getResumeCache($this->product_id);
        $randomNumbers = $this->random_numbers;
        if ($randomNumbers <= 0) {
            return [];
        }
        $limit = ($randomNumbers - 1);
        $freeNumbers = $productResume['free_numbers'];
        shuffle($freeNumbers);
        return array_slice($freeNumbers, 0, $limit);

    }

    public function getAllCartNumbers()
    {
        return array_merge($this->sortAutoNumbers(), array_values($this->getNumbersAsArray()));
    }
}
