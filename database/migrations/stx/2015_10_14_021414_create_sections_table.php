<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('movie_id')->unsigned(); 
            $table->string('title')->nullable(); 
            $table->string('view'); 
            $table->integer('sort_order'); 
            $table->string('display')->default(true);
            $table->boolean('qa');
            $table->boolean('live');
            
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
        Schema::drop('sections');
    }
}
