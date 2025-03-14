<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use ModStart\Core\Dao\ModelManageUtil;

class CreateI18nNav extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nav_i18n', function (Blueprint $table) {

            $table->increments('id');
            $table->timestamps();

            $table->string('lang', 10)->nullable()->comment('语言');
            $table->string('position', 50)->nullable()->comment('位置');

            $table->integer('pid')->nullable()->comment('上级ID');

            $table->integer('sort')->nullable()->comment('顺序');
            $table->string('name', 100)->nullable()->comment('图片');
            $table->string('link', 200)->nullable()->comment('链接');

            $table->tinyInteger('openType')->nullable()->comment('');
            $table->tinyInteger('enable')->nullable()->comment('启用');

            $table->string('icon', 50)->nullable()->comment('图标');

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
