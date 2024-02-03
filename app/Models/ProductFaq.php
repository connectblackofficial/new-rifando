<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductFaq extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = ["id", 'faq_id', 'product_id', 'order', 'show'];

    public function faq()
    {
        return $this->hasOne(Faq::class, 'id', 'faq_id');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public static function getProductFaqRelations(int $productId)
    {
        return ProductFaq::with('faq')
            ->select('faqs.title', 'product_faqs.*')
            ->join('faqs', 'faqs.id', '=', 'product_faqs.faq_id')
            ->where('product_faqs.product_id', $productId)
            ->whereNull('faqs.deleted_at')
            ->orderBy("product_faqs.order","asc")
            ->get();
    }
}
