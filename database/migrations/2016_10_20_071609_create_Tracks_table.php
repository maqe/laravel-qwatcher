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
            $table->string('payload');
            $table->integer('attempts');
            $table->timestamp('created_at');
            $table->timestamp('processing_at')->nallable();
            $table->timestamp('success_at')->nallable();
            $table->timestamp('failed_at')->nallable();
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
