<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id');
            $table->foreign('menu_id')->references('id')->on('menus');
            $table->enum('type', [1, 2])->comment('1=Divider,2=Module');
            $table->string('module_name');
            $table->string('divider_title');
            $table->string('icon_class');
            $table->string('url');
            $table->integer('order');
            $table->integer('parent_id');
            $table->enum('target', ['_self', '_blank'])->default('_self');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules');
    }
}
