<?php

namespace App\Models;

use App\Traits\ModelSiteOwnerTrait;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use ModelSiteOwnerTrait;

    protected $table = 'order';

    protected $fillable = [
        'uuid',
        'key_pix',
        'participant_id',
        'dados',
        'valor',
        'user_id'
    ];
}
