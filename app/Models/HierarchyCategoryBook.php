<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HierarchyCategoryBook extends Model
{
    protected $table = 'hierarchy_category_book';
    protected $fillable = [
        'name',
        'hierarchy_name',
        'parent_id',
        'language_id',
    ];

    // Definisikan aturan validasi
    public static function boot()
    {
        parent::boot();

        // // Aturan untuk operasi select
        // static::retrieved(function ($hierarchyCategoryBook) {
        //     // Periksa apakah id bahasa ada
        //     if (is_null($hierarchyCategoryBook->language_id)) {
        //         throw new \Exception("Language ID is required for hierarchy category book.");
        //     }
        // });

        // // Aturan untuk operasi insert
        // static::creating(function ($hierarchyCategoryBook) {
        //     // Periksa apakah id bahasa ada dan valid
        //     if (is_null($hierarchyCategoryBook->language_id)) {
        //         throw new \Exception("Language ID is required for hierarchy category book.");
        //     }
        //     // Periksa apakah id bahasa valid dengan memeriksa apakah ada di HierarchyCategoryBook
        //     $language = HierarchyCategoryBook::find($hierarchyCategoryBook->language_id);
        //     if (!$language) {
        //         throw new \Exception("Invalid language ID provided for hierarchy category book.");
        //     }
        // });
    }

    public function translations()
    {
        return $this->hasMany(Translations::class);
    }

    public function bookTranslations()
    {
        return $this->hasMany(BookTranslation::class, 'hierarchy_id');
    }
}