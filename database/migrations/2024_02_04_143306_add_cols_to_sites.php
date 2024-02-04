<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColsToSites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sites', function (Blueprint $table) {
            $cols = ['brand-color', 'btn-free-color', 'btn-reserved-color', 'btn-paid-color', 'brand-bg-color', 'secondary-bg-color', 'secondary-bg-text-color'];
            foreach ($cols as $c) {
                $table->string($c)->nullable()->after("show_purchase_notifications");
            }
        });
    }


}
