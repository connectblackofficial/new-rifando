<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColsToEnv extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consulting_environments', function (Blueprint $table) {
            $stringCols = ['banner', 'og_image', "description", "whatsapp", "email"];
            foreach ($stringCols as $c) {
                $table->string($c)->nullable()->after("active");
            }
            $boolCols = ["show_faqs", "email_required", "cpf_required", "enable_affiliates", "hide_winners"];
            foreach ($boolCols as $c) {
                $table->boolean($c)->default(true)->after("active");

            }

            $longText = ['scripts_top', 'scripts_footer', 'policy_privay', 'user_term', 'regulation'];
            foreach ($longText as $c) {
                $table->longText($c)->nullable()->after("active");
            }

            //            $table->string("banner")->nullable();
        });
    }


}
