<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SocialMediaAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_media_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('account_type');
            $table->integer('account_id');
            $table->string('screen_name');
            $table->string('account_password');
            $table->string('consumer_key');
            $table->string('consumer_secret');
            $table->string('access_token');
            $table->string('access_token_secret');
            
            $table->boolean('auto_follow');
            $table->boolean('auto_unfollow');
            $table->boolean('auto_whitelist');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('social_media_accounts');
    }
}
