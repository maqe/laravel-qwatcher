<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('driver');
            $table->integer('queue_id');
            $table->text('payload');
            $table->integer('attempts');
            $table->text('meta')->nullable();
            $table->timestamp('queue_at');
            $table->timestamp('process_at');
            $table->timestamp('succeed_at');
            $table->timestamp('failed_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tracks');
    }
}
