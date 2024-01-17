<?php

namespace App\Models;

use App\Traits\ModelSiteOwnerTrait;
use Illuminate\Database\Eloquent\Model;

class Promocao extends Model
{
    use ModelSiteOwnerTrait;

    protected $table = 'promocoes';
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
}
