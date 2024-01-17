<?php

namespace App;

use App\Models\Participant;
use App\Models\Product;
use App\Traits\ModelSiteOwnerTrait;
use Illuminate\Database\Eloquent\Model;

class GanhosAfiliado extends Model
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

    public function participante()
    {
        return $this->hasOne(Participant::class, 'id', 'participante_id')->first();
    }

    public function rifa()
    {
        return $this->hasOne(Product::class, 'id', 'product_id')->first();
    }

    public function solicitacao()
    {
        return $this->hasOne(SolicitacaoAfiliado::class, 'id', 'solicitacao_id')->first();
    }

    public function status()
    {
        $solicitacao = $this->solicitacao();
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
