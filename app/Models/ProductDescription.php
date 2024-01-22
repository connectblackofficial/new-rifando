<?php

namespace App\Models;

use App\Traits\ModelSiteOwnerTrait;
use Illuminate\Database\Eloquent\Model;

class ProductDescription extends Model
{
    use ModelSiteOwnerTrait;

    protected $table = "product_description";
    protected $fillable = ['id', 'product_id', 'description', 'video', 'user_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
