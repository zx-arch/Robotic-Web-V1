<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventManagerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_manager', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('event_code');
            $table->string('name', 50);
            $table->string('email', 100);
            $table->string('section', 50);
            $table->string('phone_number', 20);
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
        Schema::table('event_manager', function (Blueprint $table) {
            // Hapus foreign key dan indeks terlebih dahulu
            $table->dropForeign(['code_event']);
            $table->dropIndex(['code_event']);
        });

        Schema::dropIfExists('event_manager');
    }
}