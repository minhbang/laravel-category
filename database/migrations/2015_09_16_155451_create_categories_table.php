<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'categories',
            function (Blueprint $table) {
                // Nestedset attributes
                $table->increments('id');
                $table->integer('parent_id')->nullable()->index();
                $table->integer('lft')->nullable()->index();
                $table->integer('rgt')->nullable()->index();
                $table->integer('depth')->nullable();

                // Normal columns
                $table->string('title', 255);
                $table->string('slug', 255);
                $table->integer('moderator_id')->unsigned()->nullable();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('categories');
    }

}
