<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInstagramRelatedTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instagram_users', function(Blueprint $table) {
            $table->integer('id', true, true);
            $table->string('handle');
            $table->timestamps();
        });

        Schema::create('instagram_user_statistics', function(Blueprint $table) {
            $table->integer('id', true, true);
            $table->integer('instagram_user_id')->unsigned();
            $table->foreign('instagram_user_id')
                ->references('id')->on('instagram_users');

            $table->date('date');
            $table->unsignedInteger('followers');
            $table->unsignedInteger('followed_by');
            $table->unsignedInteger('posts');

            $table->timestamps();

            $table->primary(['instagram_user_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instagram_user_statistics');
        Schema::dropIfExists('instagram_users');
    }
}
