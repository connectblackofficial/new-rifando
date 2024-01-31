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
             $product->numbers = json_encode($product->getFreeNumbers(), true);
             $product->saveOrFail();
         }
        Schema::table('products', function (Blueprint $table) {
            $table->json('numbers')->charset("")->collation("")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
