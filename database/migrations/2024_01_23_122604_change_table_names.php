<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTableNames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $renames = [];
        $renames[] = ['old' => 'compras_automaticas', 'new' => 'shopping_suggestions'];
        $renames[] = ['old' => 'premios', 'new' => 'prize_draws'];
        $renames[] = ['old' => 'consulting_environments', 'new' => 'sites'];
        $renames[] = ['old' => 'ganhos_afiliados', 'new' => 'affiliate_earnings'];
        $renames[] = ['old' => 'promocoes', 'new' => 'promos'];
        $renames[] = ['old' => 'solicitacao_afiliados', 'new' => 'affiliate_withdrawal_requests'];
        $renames[] = ['old' => 'whatsapp_mensagems', 'new' => 'whatsapp_messages'];
        $renames[] = ['old' => 'rifa_afiliados', 'new' => 'affiliate_raffles'];

        //rifa_afiliados
        //whatsapp_mensagems
        foreach ($renames as $rename) {
            Schema::rename($rename['old'], $rename['new']);
        }

    }


}
