<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateI18nDemoCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('i18n_demo_category', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->timestamps();

        });

        Schema::create('i18n_demo_category_data', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->timestamps();

            $table->bigInteger('dataId')->nullable()->comment('');
            $table->string('lang', 10)->nullable()->comment('');

            $table->integer('sort')->nullable()->comment('顺序');
            $table->string('title', 20)->nullable()->comment('');
            $table->string('cover', 200)->nullable()->comment('');
            $table->string('desc', 400)->nullable()->comment('');

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
