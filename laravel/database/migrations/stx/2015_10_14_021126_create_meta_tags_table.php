<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetaTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meta_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('movie_id')->unsigned(); 
            
            $table->string('canonical')->nullable();  
            $table->string('category')->nullable();  
            $table->string('copyright')->nullable();  
            $table->string('description')->nullable();  
            $table->string('keywords')->nullable();  
            $table->string('language')->nullable();  
            $table->string('owner')->nullable();  
            $table->string('publisher')->nullable();  
            $table->string('rating')->nullable(); 
            $table->string('url')->nullable();             
            
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
        Schema::drop('meta_tags');
    }
}
