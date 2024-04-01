<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class Tutorials extends Model
{
    use SoftDeletes;
    protected $table = 'tutorials';
    protected $fillable = [
        'video_name',
        'category',
        'thumbnail',
        'path_video',
        'url',
        'status_id',
        'tutorial_category_id',
    ];

    protected static function boot()
    {
        parent::boot();
        static::checkAndCreateTable();
    }

    private static function checkAndCreateTable()
    {
        if (!Schema::hasTable('tutorials')) {
            Schema::create('tutorials', function (Blueprint $table) {
                $table->unsignedBigInteger('id')->autoIncrement();
                $table->string('video_name');
                $table->unsignedBigInteger('tutorial_category_id');
                $table->text('thumbnail');
                $table->text('path_video');
                $table->string('url');
                $table->unsignedBigInteger('status_id');
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->nullable();
                $table->softDeletes();
            });

            $data = [
                [
                    'video_name' => 'Robo Link Basic',
                    'tutorial_category_id' => 1,
                    'thumbnail' => url('/assets/youtube/product video/robo-link-thumb.png'),
                    'path_video' => url('/assets/youtube/product video/Robo Links Basic.mp4'),
                    'url' => 'https://youtu.be/ExJNoBxY23M?si=1H-s3o2SGKRM0BWI',
                    'status_id' => 4
                ],
                [
                    'video_name' => 'Instalasi Studuino Software #1',
                    'tutorial_category_id' => 2,
                    'thumbnail' => url('/assets/youtube/software requirement/Instalasi Studuino Software 1.png'),
                    'path_video' => url('/assets/youtube/software requirement/Instalasi Studuino Software 1.mp4'),
                    'url' => 'https://youtu.be/fddwkD2b7FE',
                    'status_id' => 4
                ],
                [
                    'video_name' => 'Instalasi USB Driver For Windows #2',
                    'tutorial_category_id' => 2,
                    'thumbnail' => url('/assets/youtube/software requirement/Instalasi USB Driver For Windows 2.png'),
                    'path_video' => url('/assets/youtube/software requirement/Installing USB Device Driver _ Windows.mp4'),
                    'url' => 'https://youtu.be/Re_kEa4Mm6c',
                    'status_id' => 4
                ],
                [
                    'video_name' => 'Pengenalan Komponen Sensors, LEDs & Buzzers Studuino #3',
                    'tutorial_category_id' => 2,
                    'thumbnail' => url('/assets/youtube/software requirement/Pengenalan Komponen Perangkat Studuino 3.png'),
                    'path_video' => url('/assets/youtube/software requirement/Pengenalan Komponen Perangkat Studuino 3.mp4'),
                    'url' => 'https://youtu.be/4E3y13Wwvr8',
                    'status_id' => 4
                ],
                [
                    'video_name' => 'Pengenalan Komponen DCMotors & ServoMotors Studuino #4',
                    'tutorial_category_id' => 2,
                    'thumbnail' => url('/assets/youtube/software requirement/Pengenalan Komponen Perangkat Studuino 4.png'),
                    'path_video' => url('/assets/youtube/software requirement/Pengenalan Komponen Perangkat Studuino 4.mp4'),
                    'url' => 'https://youtu.be/Ek7VpSqRi3E',
                    'status_id' => 4
                ],
                [
                    'video_name' => 'Pengenalan Komponen Perangkat Studuino #5',
                    'tutorial_category_id' => 2,
                    'thumbnail' => url('/assets/youtube/software requirement/Pengenalan Komponen Perangkat Studuino 5.png'),
                    'path_video' => url('/assets/youtube/software requirement/Pengenalan Komponen Perangkat Studuino 5.mp4'),
                    'url' => 'https://youtu.be/qOFV7Nf8w4Y',
                    'status_id' => 4
                ],
                [
                    'video_name' => 'Pengenalan Komponen Perangkat Studuino #6',
                    'tutorial_category_id' => 2,
                    'thumbnail' => url('/assets/youtube/software requirement/Pengenalan Komponen Perangkat LED Studuino 6.png'),
                    'path_video' => url('/assets/youtube/software requirement/Pengenalan Komponen Perangkat LED Studuino 6.mp4'),
                    'url' => 'https://youtu.be/qECp7LMA4LU',
                    'status_id' => 4
                ],
                [
                    'video_name' => 'Mengatur Komponen ON OFF LED #7',
                    'tutorial_category_id' => 2,
                    'thumbnail' => url('/assets/youtube/software requirement/Mengatur Komponen ON OFF LED 7.png'),
                    'path_video' => url('/assets/youtube/software requirement/Mengatur Komponen ON OFF LED 7.mp4'),
                    'url' => 'https://youtu.be/_JsCFu5hpBw',
                    'status_id' => 4
                ],
                [
                    'video_name' => 'Mengatur Waktu ON-OFF LED #8',
                    'tutorial_category_id' => 2,
                    'thumbnail' => url('/assets/youtube/software requirement/Mengatur Waktu ON-OFF LED 8.png'),
                    'path_video' => url('/assets/youtube/software requirement/Mengatur Waktu ON-OFF LED 8.mp4'),
                    'url' => 'https://youtu.be/whuyI3eIz14',
                    'status_id' => 4
                ]
            ];

            DB::table('tutorials')->insert($data);

            DB::table('tutorials')->update([
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        } elseif (Tutorials::count() == 0) {
            // Insert data jika tabel kosong
            $data = [
                [
                    'video_name' => 'Robo Link Basic',
                    'tutorial_category_id' => 1,
                    'thumbnail' => url('/assets/youtube/product video/robo-link-thumb.png'),
                    'path_video' => url('/assets/youtube/product video/Robo Links Basic.mp4'),
                    'url' => 'https://youtu.be/ExJNoBxY23M?si=1H-s3o2SGKRM0BWI',
                    'status_id' => 4
                ],
                [
                    'video_name' => 'Instalasi Studuino Software #1',
                    'tutorial_category_id' => 2,
                    'thumbnail' => url('/assets/youtube/software requirement/Instalasi Studuino Software 1.png'),
                    'path_video' => url('/assets/youtube/software requirement/Instalasi Studuino Software 1.mp4'),
                    'url' => 'https://youtu.be/fddwkD2b7FE',
                    'status_id' => 4
                ],
                [
                    'video_name' => 'Instalasi USB Driver For Windows #2',
                    'tutorial_category_id' => 2,
                    'thumbnail' => url('/assets/youtube/software requirement/Instalasi USB Driver For Windows 2.png'),
                    'path_video' => url('/assets/youtube/software requirement/Installing USB Device Driver _ Windows.mp4'),
                    'url' => 'https://youtu.be/Re_kEa4Mm6c',
                    'status_id' => 4
                ],
                [
                    'video_name' => 'Pengenalan Komponen Sensors, LEDs & Buzzers Studuino #3',
                    'tutorial_category_id' => 2,
                    'thumbnail' => url('/assets/youtube/software requirement/Pengenalan Komponen Perangkat Studuino 3.png'),
                    'path_video' => url('/assets/youtube/software requirement/Pengenalan Komponen Perangkat Studuino 3.mp4'),
                    'url' => 'https://youtu.be/4E3y13Wwvr8',
                    'status_id' => 4
                ],
                [
                    'video_name' => 'Pengenalan Komponen DCMotors & ServoMotors Studuino #4',
                    'tutorial_category_id' => 2,
                    'thumbnail' => url('/assets/youtube/software requirement/Pengenalan Komponen Perangkat Studuino 4.png'),
                    'path_video' => url('/assets/youtube/software requirement/Pengenalan Komponen Perangkat Studuino 4.mp4'),
                    'url' => 'https://youtu.be/Ek7VpSqRi3E',
                    'status_id' => 4
                ],
                [
                    'video_name' => 'Pengenalan Komponen Perangkat Studuino #5',
                    'tutorial_category_id' => 2,
                    'thumbnail' => url('/assets/youtube/software requirement/Pengenalan Komponen Perangkat Studuino 5.png'),
                    'path_video' => url('/assets/youtube/software requirement/Pengenalan Komponen Perangkat Studuino 5.mp4'),
                    'url' => 'https://youtu.be/qOFV7Nf8w4Y',
                    'status_id' => 4
                ],
                [
                    'video_name' => 'Pengenalan Komponen Perangkat Studuino #6',
                    'tutorial_category_id' => 2,
                    'thumbnail' => url('/assets/youtube/software requirement/Pengenalan Komponen Perangkat LED Studuino 6.png'),
                    'path_video' => url('/assets/youtube/software requirement/Pengenalan Komponen Perangkat LED Studuino 6.mp4'),
                    'url' => 'https://youtu.be/qECp7LMA4LU',
                    'status_id' => 4
                ],
                [
                    'video_name' => 'Mengatur Komponen ON OFF LED #7',
                    'tutorial_category_id' => 2,
                    'thumbnail' => url('/assets/youtube/software requirement/Mengatur Komponen ON OFF LED 7.png'),
                    'path_video' => url('/assets/youtube/software requirement/Mengatur Komponen ON OFF LED 7.mp4'),
                    'url' => 'https://youtu.be/_JsCFu5hpBw',
                    'status_id' => 4
                ],
                [
                    'video_name' => 'Mengatur Waktu ON-OFF LED #8',
                    'tutorial_category_id' => 2,
                    'thumbnail' => url('/assets/youtube/software requirement/Mengatur Waktu ON-OFF LED 8.png'),
                    'path_video' => url('/assets/youtube/software requirement/Mengatur Waktu ON-OFF LED 8.mp4'),
                    'url' => 'https://youtu.be/whuyI3eIz14',
                    'status_id' => 4
                ]
            ];

            Tutorials::insert($data);
        }
    }

    public static function randomVideo($count)
    {
        if ($count > 1) {
            return Tutorials::where('status_id', 4)->inRandomOrder()->take($count)->get();
        } else {
            return Tutorials::where('status_id', 4)->inRandomOrder()->first(); // Mengambil satu objek secara acak
        }
    }
    public function masterStatus()
    {
        return $this->belongsTo(MasterStatus::class, 'id');
    }
    public function categoryTutorial()
    {
        return $this->belongsTo(CategoryTutorial::class, 'id');
    }
}