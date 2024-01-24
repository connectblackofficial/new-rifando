<?php

namespace App\Models;

use App\Traits\ModelSiteOwnerTrait;
use Illuminate\Database\Eloquent\Model;

class AffiliateWithdrawalRequest extends Model
{
    use ModelSiteOwnerTrait;

    protected $fillable = [
        'afiliado_id',
        'pago',
        'user_id'
    ];

    public function affiliate()
    {
        return $this->hasOne(User::class, 'id', 'afiliado_id')->first();
    }

    public function value()
    {
        $total = AffiliateEarning::where('solicitacao_id', '=', $this->id)->sum('valor');

        return $total;

    }

    public function status()
    {

        if($this->pago){
            return '<span class="badge bg-success">PAGO</span>';
        }
        else{
            return '<span class="badge bg-warning">PENDENTE</span>';
        }
    }
}
