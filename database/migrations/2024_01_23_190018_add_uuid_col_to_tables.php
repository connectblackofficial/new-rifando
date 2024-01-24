<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Ramsey\Uuid\Uuid;

class AddUuidColToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = ['products', 'order', 'customers', 'participant', 'users', 'sites'];
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->uuid("uuid")->unique()->after("id");
            });
            $rows = DB::table($table)->get();
            foreach ($rows as $row) {
                DB::table($table)->where("id", $row->id)->update(['uuid' => Uuid::uuid4()]);
            }

        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tables', function (Blueprint $table) {
            //
        });
    }
}
