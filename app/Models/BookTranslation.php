<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BookTranslation extends Model
{
    protected $table = 'book_translation';
    protected $fillable = [
        'book_title',
        'language_id',
        'language_name',
        'status_id',
        'hierarchy_id',
        'file'
    ];

    protected static function boot()
    {
        parent::boot();

        // Event handler untuk model yang sedang dibuat
        static::creating(function ($bookTranslation) {
            // Cari atau buat entri Translation terkait
            $translation = Translations::firstOrCreate(
                ['language_name' => $bookTranslation->language_name],
                ['language_code' => Str::slug($bookTranslation->language_name)] // Ini contoh, sesuaikan dengan kolom yang sesuai
            );

            // Set language_id ke ID dari Translation yang ditemukan atau baru dibuat
            $bookTranslation->language_id = $translation->id;
        });
    }

    // Relasi dengan model Translation
    public function translations()
    {
        return $this->belongsTo(Translations::class, 'language_id');
    }

    public function hierarchyCategoryBook()
    {
        return $this->belongsTo(HierarchyCategoryBook::class, 'hierarchy_id');
    }
}