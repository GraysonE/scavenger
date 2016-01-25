<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlobalCtasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('global_ctas', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('text');
            $table->string('url');
            $table->integer('column');
            $table->integer('sort_order');
            $table->string('target')->default('self');
            
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
        Schema::drop('global_ctas');
    }
}
