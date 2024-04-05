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
    }

    public function translations()
    {
        return $this->hasMany(Translations::class);
    }

    public function bookTranslations()
    {
        return $this->hasMany(BookTranslation::class, 'hierarchy_id');
    }
    public function parentCategory()
    {
        return $this->belongsTo(HierarchyCategoryBook::class, 'parent_id');
    }
}