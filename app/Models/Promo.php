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
        return formatMoney($this->valor,false);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
