<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageTimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_time', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('message_id')->unsigned();;
            $table->foreign('message_id')
                ->references('id')
                ->on('message')
                ->onDelete('no action');

            $table->string('start_time', 5);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_time');
    }
}
