<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('social_media_accounts', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        Schema::table('friends', function (Blueprint $table) {
            $table->integer('social_media_account_id')->unsigned();
            $table->foreign('social_media_account_id')
                ->references('id')
                ->on('social_media_accounts')
                ->onDelete('cascade');
        });

        Schema::table('followers', function (Blueprint $table) {
            $table->integer('social_media_account_id')->unsigned();
            $table->foreign('social_media_account_id')
                ->references('id')
                ->on('social_media_accounts')
                ->onDelete('cascade');
        });

        Schema::table('target_users', function (Blueprint $table) {
            $table->integer('social_media_account_id')->unsigned();
            $table->foreign('social_media_account_id')
                ->references('id')
                ->on('social_media_accounts')
                ->onDelete('cascade');
        });

        Schema::table('temp_target_users', function (Blueprint $table) {
            $table->integer('social_media_account_id')->unsigned();
            $table->foreign('social_media_account_id')
                ->references('id')
                ->on('social_media_accounts')
                ->onDelete('cascade');
        });

        Schema::table('model_accounts', function (Blueprint $table) {
            $table->integer('social_media_account_id')->unsigned();
            $table->foreign('social_media_account_id')
                ->references('id')
                ->on('social_media_accounts')
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
        Schema::table('social_media_accounts', function (Blueprint $table) {
//            $table->integer('user_id');
        });

        Schema::table('im_following', function (Blueprint $table) {
//            $table->integer('social_media_account_id');
        });

        Schema::table('following_me', function (Blueprint $table) {
//            $table->integer('social_media_account_id');
        });

        Schema::table('target_users', function (Blueprint $table) {
//            $table->integer('social_media_account_id');
        });

        Schema::table('temp_target_users', function (Blueprint $table) {
//            $table->integer('social_media_account_id');
        });

        Schema::table('model_accounts', function (Blueprint $table) {
//            $table->integer('social_media_account_id');
        });
    }
}
