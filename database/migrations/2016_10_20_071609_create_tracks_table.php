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
            $table->string('queue_id');
            $table->text('payload');
            $table->integer('attempts');
            $table->string('job_name');
            $table->text('meta')->nullable();
            $table->timestamp('queue_at')->nullable();
            $table->timestamp('process_at')->nullable();
            $table->timestamp('succeed_at')->nullable();
            $table->timestamp('failed_at')->nullable();
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
