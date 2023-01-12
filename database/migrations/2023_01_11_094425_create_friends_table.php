<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friends', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("first_user", false, true);
            $table->bigInteger("second_user", false, true);
            $table->boolean("friend_status");
            $table->timestamps();


            $table->foreign('first_user', 'friends_first_user_id_fk')->references('id')->on('users');
            $table->foreign('second_user', 'friends_second_user_id_fk')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('friends');
    }
};
