<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name_common');
            $table->string('name_official');
            $table->string('tld');
            $table->string('cca2');
            $table->string('ccn3');
            $table->string('cca3');
            $table->string('cioc');
            $table->boolean('independent')->default(false); // Memberi nilai default false jika NULL
            $table->string('status');
            $table->boolean('unMember');
            $table->json('currencies');
            $table->json('idd');
            $table->string('capital');
            $table->json('altSpellings');
            $table->string('region');
            $table->string('subregion');
            $table->json('languages');
            $table->json('translations');
            $table->json('latlng');
            $table->boolean('landlocked');
            $table->json('borders');
            $table->integer('area');
            $table->string('flag');
            $table->json('demonyms');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('country');
    }
};