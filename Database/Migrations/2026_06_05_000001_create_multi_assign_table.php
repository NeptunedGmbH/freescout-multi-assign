<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMultiAssignTable extends Migration
{
    public function up()
    {
        Schema::create('multi_assign_conversation_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('conversation_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('added_by_user_id')->unsigned()->nullable();
            $table->timestamp('created_at')->nullable();

            $table->unique(['conversation_id', 'user_id'], 'ma_conv_user_unique');
            $table->index('user_id', 'ma_user_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('multi_assign_conversation_user');
    }
}
