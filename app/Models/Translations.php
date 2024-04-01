<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Translations extends Model
{
    protected $table = 'translations';
    protected $fillable = [
        'language_code',
        'language_name',
    ];

    public function hierarchyCategoryBook()
    {
        return $this->belongsTo(HierarchyCategoryBook::class);
    }
}