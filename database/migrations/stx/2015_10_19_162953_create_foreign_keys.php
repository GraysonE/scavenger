<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movies', function (Blueprint $table) {
            //
            $table->foreign('user_id')
            	->references('id')
            	->on('users')
            	->onDelete('cascade');
        });
        
        Schema::table('sections', function (Blueprint $table) {
            //
            $table->foreign('movie_id')
            	->references('id')
            	->on('movies')
            	->onDelete('cascade');
        });
        
        Schema::table('meta_tags', function (Blueprint $table) {
            //
            $table->foreign('movie_id')
            	->references('id')
            	->on('movies')
            	->onDelete('cascade');
        });
        
        Schema::table('designs', function (Blueprint $table) {
            //
            $table->foreign('movie_id')
            	->references('id')
            	->on('movies')
            	->onDelete('cascade');
        });
        
        Schema::table('people', function (Blueprint $table) {
            //
           $table->foreign('movie_id')
            	->references('id')
            	->on('movies')
            	->onDelete('cascade');
            	
            $table->foreign('section_id')
            	->references('id')
            	->on('sections')
            	->onDelete('cascade');
        });
        
        Schema::table('release_dates', function (Blueprint $table) {
            //
           $table->foreign('movie_id')
            	->references('id')
            	->on('movies')
            	->onDelete('cascade');
            	
            $table->foreign('section_id')
            	->references('id')
            	->on('sections')
            	->onDelete('cascade');
        });
        
        Schema::table('social_media', function (Blueprint $table) {
            //
           $table->foreign('movie_id')
            	->references('id')
            	->on('movies')
            	->onDelete('cascade');
        });
        
        Schema::table('tag_lines', function (Blueprint $table) {
            //
           $table->foreign('movie_id')
            	->references('id')
            	->on('movies')
            	->onDelete('cascade');
            	
            $table->foreign('section_id')
            	->references('id')
            	->on('sections')
            	->onDelete('cascade');
        });
        
        Schema::table('tickets', function (Blueprint $table) {
            //
           $table->foreign('movie_id')
            	->references('id')
            	->on('movies')
            	->onDelete('cascade');
            	
            $table->foreign('section_id')
            	->references('id')
            	->on('sections')
            	->onDelete('cascade');
        });
        
        Schema::table('videos', function (Blueprint $table) {
            //
           $table->foreign('movie_id')
            	->references('id')
            	->on('movies')
            	->onDelete('cascade');
            	
            $table->foreign('section_id')
            	->references('id')
            	->on('sections')
            	->onDelete('cascade');
        });
        
        Schema::table('call_to_actions', function (Blueprint $table) {
            //
           $table->foreign('movie_id')
            	->references('id')
            	->on('movies')
            	->onDelete('cascade');
            	
            $table->foreign('section_id')
            	->references('id')
            	->on('sections')
            	->onDelete('cascade');
        });
        
        Schema::table('featured_contents', function (Blueprint $table) {
            //
           $table->foreign('movie_id')
            	->references('id')
            	->on('movies')
            	->onDelete('cascade');
            	
            $table->foreign('section_id')
            	->references('id')
            	->on('sections')
            	->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('movies', function (Blueprint $table) {
            //
        });
        
        Schema::table('designs', function (Blueprint $table) {
            //
        });
        
        Schema::table('images', function (Blueprint $table) {
            //
        });
        
        Schema::table('meta_tags', function (Blueprint $table) {
            //
        });
        
        Schema::table('awards', function (Blueprint $table) {
            //
        });

		Schema::table('people_involved', function (Blueprint $table) {
            //
        });

		Schema::table('release_dates', function (Blueprint $table) {
            //
        });

		Schema::table('reviews', function (Blueprint $table) {
            //
        });

		Schema::table('sections', function (Blueprint $table) {
            //
        });
        
        Schema::table('social_media', function (Blueprint $table) {
            //
        });
        
        Schema::table('tag_lines', function (Blueprint $table) {
            //
        });
        
        Schema::table('tickets', function (Blueprint $table) {
            //
        });
        
        Schema::table('users', function (Blueprint $table) {
            //
        });
        
        Schema::table('videos', function (Blueprint $table) {
            //
        });
        
        Schema::table('call_to_actions', function (Blueprint $table) {
            //
        });
        
        Schema::table('featured_content', function (Blueprint $table) {
            //
        });
        
    }
}
