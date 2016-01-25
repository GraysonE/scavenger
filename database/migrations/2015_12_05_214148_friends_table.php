<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImFollowingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friends', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('account_id');
            $table->string('screen_name');
            $table->boolean('user_followed');
            $table->date('followed_timestamp');
            $table->date('unfollowed_timestamp');
            $table->boolean('whitelisted');
            $table->date('last_active');
            $table->boolean('to_unfollow');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('friends');
    }
}
