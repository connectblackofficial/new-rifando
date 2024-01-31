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
        $participants = \App\Models\Participant::whereNotNull('numbers')->get();
        foreach ($participants as $participant) {
            $participant->numbers = json_encode(explode(",", $participant->numbers));
            $participant->saveOrFail();
        }
        Schema::table('participant', function (Blueprint $table) {
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
        Schema::table('json', function (Blueprint $table) {
            //
        });
    }
}
