<?php

namespace App\Models;

use App\Traits\ModelSiteOwnerTrait;
use Illuminate\Database\Eloquent\Model;

class AffiliateEarning extends Model
{
    use ModelSiteOwnerTrait;

    protected $fillable = [
        'product_id',
        'participante_id',
        'afiliado_id',
        'solicitacao_id',
        'valor',
        'pago',
        'user_id'
    ];

    public function participant()
    {
        return $this->hasOne(Participant::class, 'id', 'participante_id')->first();
    }

    public function rifa()
    {
        return $this->hasOne(Product::class, 'id', 'product_id')->first();
    }

    public function affiliateRequest()
    {
        return $this->hasOne(AffiliateWithdrawalRequest::class, 'id', 'solicitacao_id')->first();
    }

    public function status()
    {
        $solicitacao = $this->affiliateRequest();
        if($solicitacao != null){
            if($solicitacao->pago){
                return '<span class="badge bg-success">RECEBIDO</span>';
            }
            else{
                return '<span class="badge bg-warning" style="color: #000 !important; font-weight: bold">SOLICITADO</span>';
            }
            
        }
        else{
            return '<span class="badge bg-primary">DISPONÍVEL</span>';
        }
    }
}
