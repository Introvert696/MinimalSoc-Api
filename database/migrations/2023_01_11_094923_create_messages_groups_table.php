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
        Schema::create('messages_groups', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Диалог');
            $table->bigInteger('first_user', false, true);
            $table->bigInteger('second_user', false, true);
            $table->timestamps();


            $table->foreign('first_user', 'messages_groups_first_user_id_fk')->references('id')->on('users');
            $table->foreign('second_user', 'messages_groups_second_user_id_fk')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages_groups');
    }
};
