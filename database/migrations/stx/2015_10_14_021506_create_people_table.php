<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('movie_id')->unsigned(); 
            $table->integer('section_id')->unsigned(); 
            
            $table->integer('sort_order');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('occupation');
            $table->longText('biography');
            $table->string('display');
            
            $table->string('role');
            
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
        Schema::drop('people');
    }
}
