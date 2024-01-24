<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColToCarts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->bigInteger('promo_id')->unsigned()->nullable()->after("participant_id");
            $table->foreign('promo_id')->references('id')->on('promos')->onDelete('cascade')->onUpdate('cascade');
        });
    }

}
