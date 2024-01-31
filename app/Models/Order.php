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

    public function participant()
    {
        return $this->hasOne(Participant::class, 'id', 'participant_id');
    }

    public function getData()
    {
        return json_decode($this->dados);
    }
}
