<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListIpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_ip', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('network');
            $table->string('netmask');
            $table->string('geoname_id');
            $table->string('continent_code');
            $table->string('continent_name');
            $table->string('country_iso_code');
            $table->string('country_name');
            $table->boolean('is_anonymous_proxy');
            $table->boolean('is_satellite_provider');
            $table->boolean('is_blocked')->default(false);
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
        Schema::dropIfExists('list_ip');
    }
}