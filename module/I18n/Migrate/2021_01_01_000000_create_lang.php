<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lang', function (Blueprint $table) {

            $table->increments('id');
            $table->timestamps();

            $table->integer('sort')->nullable()->comment('');
            $table->string('name', 100)->nullable()->comment('');
            $table->string('shortName', 10)->nullable()->comment('');
            $table->string('image', 200)->nullable()->comment('');

            $table->tinyInteger('enable')->nullable()->comment('');
            $table->tinyInteger('isDefault')->nullable()->comment('');

        });

        \ModStart\Core\Dao\ModelUtil::insertAll('lang', [
            [
                'sort' => 1,
                'name' => '简体中文',
                'shortName' => 'zh',
                'enable' => true,
                'isDefault' => true,
                'image' => '/vendor/I18n/lang-flags/cn.png',
            ],
            [
                'sort' => 2,
                'name' => 'English',
                'shortName' => 'en',
                'enable' => true,
                'isDefault' => false,
                'image' => '/vendor/I18n/lang-flags/us.png',
            ],
        ]);
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
