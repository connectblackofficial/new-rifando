<?php

namespace App;

use App\Traits\ModelSiteOwnerTrait;
use Illuminate\Database\Eloquent\Model;

class RifaAfiliado extends Model
{
    use ModelSiteOwnerTrait;

    protected $fillable = [
        'product_id',
        'afiliado_id',
        'token',
        'user_id'
    ];
}
