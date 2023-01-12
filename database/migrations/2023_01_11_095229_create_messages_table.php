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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('from', false, true);
            $table->bigInteger('to', false, true);
            $table->bigInteger('message_group', false, true);
            $table->text('content');
            $table->timestamps();

            $table->foreign('from', 'messages_from_id_fk')->references('id')->on('users');
            $table->foreign('to', 'messages_to_id_fk')->references('id')->on('users');
            $table->foreign('message_group', 'messages_message_group_id_fk')->references('id')->on('messages_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
