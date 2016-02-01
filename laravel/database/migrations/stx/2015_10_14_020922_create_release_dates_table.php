<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReleaseDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('release_dates', function (Blueprint $table) {
            
            $table->increments('id');
            $table->integer('movie_id')->unsigned(); 
            $table->integer('section_id')->unsigned(); 
            
            $table->boolean('actual')->default(0);
            $table->string('text')->nullable();  
            $table->string('type')->nullable();  
            $table->date('date');            
            
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
        Schema::drop('release_dates');
    }
}
