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
        Schema::create('hierarchy_category_book', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('hierarchy_name');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('language_id');
            $table->timestamps();
        });

        $data = [
            ['name' => 'Block Programming', 'hierarchy_name' => 'Block Programming', 'parent_id' => 0, 'language_id' => 7],
            ['name' => 'Python Programming', 'hierarchy_name' => 'Python Programming', 'parent_id' => 0, 'language_id' => 7],
            ['name' => 'Basic', 'hierarchy_name' => 'Block Programming > Basic', 'parent_id' => 1, 'language_id' => 7],
            ['name' => 'Intermediate', 'hierarchy_name' => 'Block Programming > Intermediate', 'parent_id' => 1, 'language_id' => 7],
            ['name' => 'Advanced', 'hierarchy_name' => 'Block Programming > Advanced', 'parent_id' => 1, 'language_id' => 7],
            ['name' => 'Stop and Go', 'hierarchy_name' => 'Block Programming > Basic > Stop and Go', 'parent_id' => 3, 'language_id' => 7],
            ['name' => 'Making a Light Show', 'hierarchy_name' => 'Block Programming > Basic > Making a Light Show', 'parent_id' => 3, 'language_id' => 7],
            ['name' => 'Making a Robot Car', 'hierarchy_name' => 'Block Programming > Basic > Making a Robot Car', 'parent_id' => 3, 'language_id' => 7],
            ['name' => 'Automatic Doors', 'hierarchy_name' => 'Block Programming > Basic > Automatic Doors', 'parent_id' => 3, 'language_id' => 7],
            ['name' => 'Controlling a Motor Cars', 'hierarchy_name' => 'Block Programming > Intermediate > Controlling a Motor Cars', 'parent_id' => 4, 'language_id' => 7],
            ['name' => 'Electronic Instrument', 'hierarchy_name' => 'Block Programming > Intermediate > Electronic Instrument', 'parent_id' => 4, 'language_id' => 7],
            ['name' => 'Robots at Work', 'hierarchy_name' => 'Block Programming > Intermediate > Robots at Work', 'parent_id' => 4, 'language_id' => 7],
            ['name' => 'Machines and Mechanisms', 'hierarchy_name' => 'Block Programming > Intermediate > Machines and Mechanisms', 'parent_id' => 4, 'language_id' => 7],
            ['name' => 'The World of Games', 'hierarchy_name' => 'Block Programming > Intermediate > The World of Games', 'parent_id' => 4, 'language_id' => 7],
            ['name' => 'Playing with Controllers', 'hierarchy_name' => 'Block Programming > Advanced > Playing with Controllers', 'parent_id' => 5, 'language_id' => 7],
            ['name' => 'All About Walkbots', 'hierarchy_name' => 'Block Programming > Advanced > All About Walkbots', 'parent_id' => 5, 'language_id' => 7],
            ['name' => 'The Factory Scanbot', 'hierarchy_name' => 'Block Programming > Advanced > The Factory Scanbot', 'parent_id' => 5, 'language_id' => 7],
            ['name' => 'Advanced Game Making', 'hierarchy_name' => 'Block Programming > Advanced > Advanced Game Making', 'parent_id' => 5, 'language_id' => 7],
            ['name' => 'Introduction', 'hierarchy_name' => 'Python Programming > Introduction', 'parent_id' => 20, 'language_id' => 7],
            ['name' => 'Programming Basic', 'hierarchy_name' => 'Python Programming > Programming Basic', 'parent_id' => 2, 'language_id' => 7],
            ['name' => 'Programming Basic part 1', 'hierarchy_name' => 'Python Programming > Programming Basic > Programming Basic part 1', 'parent_id' => 20, 'language_id' => 7],
            ['name' => 'Programming Basic part 2', 'hierarchy_name' => 'Python Programming > Programming Basic > Programming Basic part 2', 'parent_id' => 20, 'language_id' => 7],
            ['name' => 'Programming Intermediate', 'hierarchy_name' => 'Python Programming > Programming Intermediate', 'parent_id' => 2, 'language_id' => 7],
            ['name' => 'Programming Hands On part 1', 'hierarchy_name' => 'Python Programming > Programming Advanced > Programming Hands On part 1', 'parent_id' => 23, 'language_id' => 7],
            ['name' => 'Programming Hands On part 2', 'hierarchy_name' => 'Python Programming > Programming Advanced > Programming Hands On part 2', 'parent_id' => 23, 'language_id' => 7],
            ['name' => 'Programming Advanced', 'hierarchy_name' => 'Python Programming > Programming Advanced', 'parent_id' => 2, 'language_id' => 7],
            ['name' => 'Programming Advanced 3', 'hierarchy_name' => 'Python Programming > Programming Advanced > Programming Advanced 3', 'parent_id' => 26, 'language_id' => 7],
            ['name' => 'Pemrograman Berbasis Blok', 'hierarchy_name' => 'Pemrograman Berbasis Blok', 'parent_id' => 0, 'language_id' => 67],
            ['name' => 'Pemrograman Berbasis Python', 'hierarchy_name' => 'Pemrograman Berbasis Python', 'parent_id' => 0, 'language_id' => 67],
            ['name' => 'Dasar', 'hierarchy_name' => 'Block Programming > Dasar', 'parent_id' => 28, 'language_id' => 67],
            ['name' => 'Menengah', 'hierarchy_name' => 'Block Programming > Menengah', 'parent_id' => 28, 'language_id' => 67],
            ['name' => 'Lanjutan', 'hierarchy_name' => 'Block Programming > Lanjutan', 'parent_id' => 28, 'language_id' => 67],
            ['name' => 'Membuat Pertunjukan Cahaya', 'hierarchy_name' => 'Block Programming > Dasar > Membuat Pertunjukan Cahaya', 'parent_id' => 29, 'language_id' => 67],
            ['name' => 'Membuat Mobil Robot', 'hierarchy_name' => 'Block Programming > Dasar > Membuat Mobil Robot', 'parent_id' => 29, 'language_id' => 67],
            ['name' => 'AI Programming', 'hierarchy_name' => 'AI Programming', 'parent_id' => 0, 'language_id' => 7],
            ['name' => 'Getting Started With AI_JE', 'hierarchy_name' => 'AI Programming > Getting Started With AI_JE', 'parent_id' => 35, 'language_id' => 7],
            ['name' => 'Talking to Robots_JE', 'hierarchy_name' => 'AI Programming > Talking to Robots_JE', 'parent_id' => 35, 'language_id' => 7],
            ['name' => 'Seeing with AI', 'hierarchy_name' => 'AI Programming > Seeing with AI', 'parent_id' => 35, 'language_id' => 7],
            ['name' => 'Sorting the Garbage', 'hierarchy_name' => 'AI Programming > Sorting the Garbage', 'parent_id' => 35, 'language_id' => 7],
            ['name' => 'Smarter Cars', 'hierarchy_name' => 'AI Programming > Smarter Cars', 'parent_id' => 35, 'language_id' => 7],
            ['name' => 'Rock, Paper, Scissors', 'hierarchy_name' => 'AI Programming > Rock, Paper, Scissors', 'parent_id' => 35, 'language_id' => 7],
            ['name' => 'Robotic Companions', 'hierarchy_name' => 'AI Programming > Robotic Companions', 'parent_id' => 35, 'language_id' => 7],
        ];

        DB::table('hierarchy_category_book')->insert($data);

        DB::table('hierarchy_category_book')->update([
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hierarchy_category_book');
    }
};