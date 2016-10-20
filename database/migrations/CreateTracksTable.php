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
            $table->string('queue')->comment('The name of Queue this job is in');
            $table->string('connection')->comment('connection of queue to track queue driver');
            $table->integer('priority');
            $table->integer('attemp')->comment('Provides for retries, but still fail ');
            $table->string('handler')->comment('string of the object that will do work');
            $table->text('parameter')->comment('parameters of handler');
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
