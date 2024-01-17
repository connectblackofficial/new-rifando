<?php

namespace App\Models;

use App\Traits\ModelSiteOwnerTrait;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use ModelSiteOwnerTrait;

    protected $table = 'products_images';
    protected $fillable = ['id', 'name', 'product_id', 'user_id'];

    public function produtos_imagen()
    {
        return $this->belongsTo(Product::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
