<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageTimeInTimeZoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_time_in_time_zone', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('message_time_id')->unsigned();;
            $table->foreign('message_time_id')
                ->references('id')
                ->on('message_time')
                ->onDelete('no action');

            $table->integer('timezone_shift');
            $table->string('time_in_timezome');

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
        Schema::dropIfExists('message_time_in_time_zone');
    }
}
