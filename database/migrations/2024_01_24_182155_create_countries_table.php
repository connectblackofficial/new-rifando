<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('code');
            $table->string('dial_code');
        });
        $countries = json_decode(file_get_contents(resource_path("files/countries.json")), true);
        foreach ($countries as $country){
            DB::table('countries')->insert([
                'name' => $country['name'],
                'code' => $country['code'],
                'dial_code' => $country['dial_code']
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
