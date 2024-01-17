<?php

namespace App\Models;

use App\Traits\ModelSiteOwnerTrait;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use ModelSiteOwnerTrait;

    protected $fillable = [
        'nome',
        'telephone',
        'email',
        'cpf',
        'user_id'
    ];
}
