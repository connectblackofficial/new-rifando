<?php

namespace App\Models;

use App\Traits\ModelSiteOwnerTrait;
use Illuminate\Database\Eloquent\Model;

class PrizeDraw extends Model
{
    use ModelSiteOwnerTrait;

    protected $fillable = [
        'product_id',
        'participant_id',
        'ordem',
        'telefone',
        'descricao',
        'ganhador',
        'cota',
        'foto',
        'user_id'
    ];

    public function rifa()
    {
        return $this->hasOne(Product::class, 'id', 'product_id')->first();
    }

    public function participant()
    {
        if ($this->participant_id != null) {
            return $this->hasOne(Participant::class, 'id', 'participant_id')->first();
        } else {
            return new Participant();
        }

    }

    public function linkWpp()
    {
        $tel = "55" . str_replace(["(", ")", "-", " "], "", $this->telefone);
        $link = 'https://api.whatsapp.com/send?phone=' . $tel;

        return $link;
    }

    public function scopeWinners($query)
    {
        return $query->where('descricao', '!=', '')->where('ganhador', '!=', '');
    }

}
