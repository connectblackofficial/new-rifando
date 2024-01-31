<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTextColsToJson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       $products = \App\Models\Product::whereNotNull('numbers')->get();

        foreach ($products as $product) {
            $newNumbers = [];
            $numbers = $product->getFreeNumbers();
            foreach ($numbers as $k => $v) {
                $newNumbers[$v] = $v;
            }
            $product->numbers = json_encode($newNumbers);
            $product->saveOrFail();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('json', function (Blueprint $table) {
            //
        });
    }
}
