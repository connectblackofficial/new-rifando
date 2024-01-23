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
    }


}
