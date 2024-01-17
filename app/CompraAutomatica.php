<?php

namespace App;

use App\Traits\ModelSiteOwnerTrait;
use Illuminate\Database\Eloquent\Model;

class CompraAutomatica extends Model
{
    use ModelSiteOwnerTrait;

    protected $table = 'compras_automaticas';

    protected $fillable = [
        'product_id',
        'qtd',
        'popular',
        'user_id'
    ];
}
