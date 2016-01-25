<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModelAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('screen_name');
            $table->integer('model_user_id');
            $table->integer('sort_order')->nullable();
            $table->integer('api_cursor', 30)->default("-1");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('model_accounts');
    }
}
