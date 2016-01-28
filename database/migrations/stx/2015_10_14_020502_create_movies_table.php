<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            
            $table->string('environment', 4);
            $table->integer('sort_order');
            $table->string('title');
            $table->longText('about')->nullable();
            $table->string('legal_line')->nullable();
            $table->string('google_analytics_id');
            $table->string('mobile_google_analytics_id');
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
        Schema::drop('movies');
    }
}
