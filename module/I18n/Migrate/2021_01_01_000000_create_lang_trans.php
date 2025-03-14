<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLangTrans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lang_trans', function (Blueprint $table) {

            $table->increments('id');
            $table->timestamps();

            $table->string('lang', 10)->nullable()->comment('');
            $table->string('name', 200)->nullable()->comment('');
            $table->string('trans', 200)->nullable()->comment('');

            $table->unique(['lang', 'id']);

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