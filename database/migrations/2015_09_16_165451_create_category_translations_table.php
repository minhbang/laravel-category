<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryTranslationsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'category_translations',
            function (Blueprint $table) {
                $table->increments('id');

                $table->string('title', 255);
                $table->string('slug', 255);

                $table->integer('category_id')->unsigned();
                $table->string('locale', '10')->index();
                $table->unique(['category_id', 'locale']);
                $table->unique(['slug', 'locale']);
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
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
        Schema::drop('category_translations');
    }

}
