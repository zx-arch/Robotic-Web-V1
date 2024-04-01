<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('book_translation', function (Blueprint $table) {
            $table->id();
            $table->string('book_title');
            $table->unsignedBigInteger('language_id');
            $table->string('language_name');
            $table->unsignedBigInteger('pages');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('hierarchy_id');
            $table->string('file');
            $table->timestamps();
            $table->softDeletes(); // Soft delete column
        });

        // Insert data
        $data = [
            [
                'book_title' => 'Stop and Go',
                'language_name' => 'English',
                'language_id' => 7,
                'status_id' => 1,
                'hierarchy_id' => 6,
                'file' => 'Basic 01_Stop and Go.pdf',
                'pages' => 48,
            ],
            [
                'book_title' => 'Making a Light Show',
                'language_name' => 'English',
                'language_id' => 7,
                'status_id' => 1,
                'hierarchy_id' => 7,
                'file' => 'Basic 02_Making a Light Show.pdf',
                'pages' => 56,
            ],
            [
                'book_title' => 'Making a Robot Car',
                'language_name' => 'English',
                'language_id' => 7,
                'status_id' => 1,
                'hierarchy_id' => 8,
                'file' => 'Basic 03_Making a Robot Car.pdf',
                'pages' => 48,
            ],
            [
                'book_title' => 'Automatic Doors',
                'language_name' => 'English',
                'language_id' => 7,
                'status_id' => 1,
                'hierarchy_id' => 9,
                'file' => 'Basic 04_Automatic Doors.pdf',
                'pages' => 56,
            ],
            [
                'book_title' => 'Controlling a Motor Cars',
                'language_name' => 'English',
                'language_id' => 7,
                'status_id' => 1,
                'hierarchy_id' => 10,
                'file' => 'Intermediate 01_Controlling Motor Cars.pdf',
                'pages' => 40,
            ],
            [
                'book_title' => 'Electronic Instrument',
                'language_name' => 'English',
                'language_id' => 7,
                'status_id' => 1,
                'hierarchy_id' => 11,
                'file' => 'Intermediate 02_Electronic Instruments.pdf',
                'pages' => 48,
            ],
            [
                'book_title' => 'Robots at Work',
                'language_name' => 'English',
                'language_id' => 7,
                'status_id' => 1,
                'hierarchy_id' => 12,
                'file' => 'Intermediate 03_Robots at Works.pdf',
                'pages' => 48,
            ],
            [
                'book_title' => 'Machines and Mechanisms',
                'language_name' => 'English',
                'language_id' => 7,
                'status_id' => 1,
                'hierarchy_id' => 13,
                'file' => 'Intermediate 04_Machines and Mechanisms.pdf',
                'pages' => 52,
            ],
            [
                'book_title' => 'The World of Games',
                'language_name' => 'English',
                'language_id' => 7,
                'status_id' => 1,
                'hierarchy_id' => 14,
                'file' => 'Intermediate 05_The World of Games.pdf',
                'pages' => 48,
            ],
            [
                'book_title' => 'Playing with Controllers',
                'language_name' => 'English',
                'language_id' => 7,
                'status_id' => 1,
                'hierarchy_id' => 15,
                'file' => 'Advanced 01_Playing with Controllers.pdf',
                'pages' => 56,
            ],
            [
                'book_title' => 'All About Walkbots',
                'language_name' => 'English',
                'language_id' => 7,
                'status_id' => 1,
                'hierarchy_id' => 16,
                'file' => 'Advanced 02_All About Walkbotss.pdf',
                'pages' => 40,
            ],
            [
                'book_title' => 'The Factory Scanbot',
                'language_name' => 'English',
                'language_id' => 7,
                'status_id' => 1,
                'hierarchy_id' => 17,
                'file' => 'Advanced 03_The Factory Scanbots.pdf',
                'pages' => 52,
            ],
            [
                'book_title' => 'Advanced Game Making',
                'language_name' => 'English',
                'language_id' => 7,
                'status_id' => 1,
                'hierarchy_id' => 18,
                'file' => 'Advanced 04_Advanced Game Makings.pdf',
                'pages' => 60,
            ],
            [
                'book_title' => 'Introduction',
                'language_name' => 'English',
                'language_id' => 7,
                'status_id' => 1,
                'hierarchy_id' => 19,
                'file' => 'Introduction.pdf',
                'pages' => 24,
            ],
            [
                'book_title' => 'Programming Basic 1',
                'language_name' => 'English',
                'language_id' => 7,
                'status_id' => 1,
                'hierarchy_id' => 21,
                'file' => 'Programming Basics_Part 1.pdf',
                'pages' => 20,
            ],
            [
                'book_title' => 'Programming Basic 2',
                'language_name' => 'English',
                'language_id' => 7,
                'status_id' => 1,
                'hierarchy_id' => 22,
                'file' => 'Programming Basics_Part 2.pdf',
                'pages' => 40,
            ],
            [
                'book_title' => 'Programming Hands On part 1',
                'language_name' => 'English',
                'language_id' => 7,
                'status_id' => 1,
                'hierarchy_id' => 24,
                'file' => 'Hands-on Programming_Part 1.pdf',
                'pages' => 28,
            ],
            [
                'book_title' => 'Programming Hands On part 2',
                'language_name' => 'English',
                'language_id' => 7,
                'status_id' => 1,
                'hierarchy_id' => 25,
                'file' => '88381_Python_Programing_Hands-On Part 2.pdf',
                'pages' => 12,
            ],
            [
                'book_title' => 'Programming Advanced 3',
                'language_name' => 'English',
                'language_id' => 7,
                'status_id' => 1,
                'hierarchy_id' => 27,
                'file' => '88387_Python_Programing_Advanced_Programing 3_ETC.pdf',
                'pages' => 12,
            ],
            [
                'book_title' => 'Getting Started With AI_JE',
                'language_name' => 'English',
                'language_id' => 7,
                'status_id' => 1,
                'hierarchy_id' => 36,
                'file' => '01_Getting Started with AI_JE.pdf',
                'pages' => 35,
            ],
            [
                'book_title' => 'Talking to Robots_JE',
                'language_name' => 'English',
                'language_id' => 7,
                'status_id' => 1,
                'hierarchy_id' => 37,
                'file' => '02_Talking to Robots_JE.pdf',
                'pages' => 37,
            ],
            [
                'book_title' => 'Seeing with AI',
                'language_name' => 'English',
                'language_id' => 7,
                'status_id' => 1,
                'hierarchy_id' => 38,
                'file' => '03_Seeing with AI.pdf',
                'pages' => 43,
            ],
            [
                'book_title' => 'Sorting the Garbage',
                'language_name' => 'English',
                'language_id' => 7,
                'status_id' => 1,
                'hierarchy_id' => 39,
                'file' => '04_Sorting the Garbage.pdf',
                'pages' => 34,
            ],
            [
                'book_title' => 'Smarter Cars',
                'language_name' => 'English',
                'language_id' => 7,
                'status_id' => 1,
                'hierarchy_id' => 40,
                'file' => '05_Smarter Cars.pdf',
                'pages' => 38,
            ],
            [
                'book_title' => 'Rock, Paper, Scissors',
                'language_name' => 'English',
                'language_id' => 7,
                'status_id' => 1,
                'hierarchy_id' => 41,
                'file' => '06_Rock, Paper, Scissors.pdf',
                'pages' => 36,
            ],
            [
                'book_title' => 'Robotic Companions',
                'language_name' => 'English',
                'language_id' => 7,
                'status_id' => 1,
                'hierarchy_id' => 42,
                'file' => '07_Robotic Companions.pdf',
                'pages' => 38,
            ],
            [
                'book_title' => 'Membuat Pertunjukan Cahaya',
                'language_name' => 'Indonesian',
                'language_id' => 67,
                'status_id' => 1,
                'hierarchy_id' => 30,
                'file' => 'Dasar 02_Membuat Pertunjukan Cahaya.pdf',
                'pages' => 57,
            ],
            [
                'book_title' => 'Membuat Mobil Robot',
                'language_name' => 'Indonesian',
                'language_id' => 67,
                'status_id' => 1,
                'hierarchy_id' => 30,
                'file' => 'Dasar 03_Membuat Mobil Robot.pdf',
                'pages' => 48,
            ],
            // Masukkan data lainnya di sini...
        ];

        foreach ($data as $bookData) {
            DB::table('book_translation')->insert($bookData);
        }

        DB::table('book_translation')->update([
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_translation');
    }
};