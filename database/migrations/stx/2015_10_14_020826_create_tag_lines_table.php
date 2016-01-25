<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('movie_id')->unsigned(); 
            $table->integer('section_id')->unsigned(); 
            
            $table->string('text')->nullable();
                       
            
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
        Schema::drop('tag_lines');
    }
}
