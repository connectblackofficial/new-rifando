<?php

namespace App\Models;

use App\Traits\ModelSiteOwnerTrait;
use Illuminate\Database\Eloquent\Model;

class AffiliateRaffle extends Model
{
    use ModelSiteOwnerTrait;

    protected $fillable = [
        'product_id',
        'afiliado_id',
        'token',
        'user_id'
    ];
}
