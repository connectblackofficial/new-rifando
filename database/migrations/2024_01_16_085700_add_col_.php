<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = ["auto_messages", "compras_automaticas", "customers", "drop_participants", "drop_payment_pix", "ganhos_afiliados", "messages", "order", "participant", "payment_pix", "premios", "product_description", "products_images", "promocoes", "raffles", "rifa_afiliados", "solicitacao_afiliados", "videos", "whatsapp_mensagems"];
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->integer('user_id')->nullable()->unsigned()->after('id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            });


        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
