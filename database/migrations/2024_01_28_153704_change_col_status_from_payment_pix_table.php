<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColStatusFromPaymentPixTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_pix', function (Blueprint $table) {
            $table->enum('status', \App\Enums\PaymentPixStatusEnum::getValues())->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_pix', function (Blueprint $table) {
            //
        });
    }
}
