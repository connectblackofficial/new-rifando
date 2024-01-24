<?php

namespace App\Models;

use App\Traits\ModelSiteOwnerTrait;
use Illuminate\Database\Eloquent\Model;

class ShoppingSuggestion extends Model
{
    use ModelSiteOwnerTrait;

    protected $table = 'shopping_suggestions';

    protected $fillable = [
        'product_id',
        'qtd',
        'popular',
        'user_id'
    ];
}
