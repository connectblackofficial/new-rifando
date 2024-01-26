<?php

namespace App\Models;

use App\Traits\ModelSiteOwnerTrait;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use ModelSiteOwnerTrait;

    protected $table = 'promos';
    protected $fillable = [
        'qtdNumeros',
        'ordem',
        'desconto',
        'valor',
        'product_id',
        'user_id'
    ];

    public function valorFormatted()
    {
        return number_format($this->valor, 2, ",", ".");
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
