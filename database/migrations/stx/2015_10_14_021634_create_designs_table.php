<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDesignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('designs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('movie_id')->unsigned(); 
            $table->integer('section_id')->unsigned()->nullable(); 
            // Fonts
            $table->string('global_navigation_font');
            $table->string('header_font');
            $table->string('paragraph_font');
            $table->string('footer_font');
            // BG colors
            $table->string('custom_background')->default('#fff');
            $table->string('desktop_background_color')->default('#fff');
            $table->string('mobile_background_color')->default('#fff');
            // Button colors
            $table->string('desktop_buttons_color')->default('#E74531');
            $table->string('mobile_buttons_color')->default('#E74531');
            // Font color
            $table->string('desktop_header_color')->default('#fff');
            $table->string('mobile_header_color')->default('#fff');
            $table->string('desktop_paragraph_color')->default('#333');
            $table->string('mobile_paragraph_color')->default('#333');
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
        Schema::drop('designs');
    }
}
