<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeaturedContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('featured_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('movie_id')->unsigned(); 
            $table->integer('section_id')->unsigned();
            
            $table->string('url')->nullable();
            $table->string('cta_text')->nullable();
            $table->string('type');
            $table->string('name');
            $table->longText('body');
            $table->integer('sort_order');
            
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
        Schema::drop('featured_contents');
    }
}
