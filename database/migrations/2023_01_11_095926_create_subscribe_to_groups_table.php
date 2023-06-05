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
        Schema::create('subscribe_to_groups', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('group_id', false, true);
            $table->bigInteger('user_id', false, true);
            $table->timestamps();

            $table->foreign('group_id', 'subscribe_to_groups_group_id_id_fk')->references('id')->on('groups');
            $table->foreign('user_id', 'subscribe_to_groups_user_id_id_fk')->references('id')->on('users');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscribe_to_groups');
    }
};
