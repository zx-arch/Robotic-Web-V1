<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookTranslation;
use App\Models\Translations;
use App\Models\HierarchyCategoryBook;

class MaterialsController extends Controller
{
    public function index()
    {
        $hierarchyCategories = HierarchyCategoryBook::get();

        // Mengambil data dari tabel book_translations
        $versionBook = BookTranslation::select('language_id', 'language_name')
            ->groupBy('language_id', 'language_name')
            ->where('deleted_at', null)
            ->where('status_id', 1)
            ->get();

        $hierarchyData = $hierarchyCategories->pluck('hierarchy_name')->toArray();

        // Objek untuk menyimpan jumlah kategori setiap tingkat hierarki
        $categoryCount = [];

        // Iterasi melalui data hierarki
        collect($hierarchyData)->each(function ($item) use (&$categoryCount) {
            // Memisahkan setiap hierarki menggunakan pemisah '>'
            $categories = explode(' > ', $item);
            // Menghitung jumlah kategori
            $count = count($categories);
            // Menambahkan jumlah kategori ke objek categoryCount
            $categoryCount[$count] = ($categoryCount[$count] ?? 0) + 1;
        });

        // Mengidentifikasi kategori dengan jumlah terbanyak
        $maxCount = max($categoryCount);

        // Menampilkan kategori dengan jumlah terbanyak
        $maxCategory = array_search($maxCount, $categoryCount);
        //dd($maxCategory);
        //dd($versionBook);
        return view('partial.materials', compact('versionBook', 'hierarchyCategories', 'maxCategory'));
    }

    public function find(Request $request)
    {
        //dd($request->all());
        $getBook = BookTranslation::where('hierarchy_id', $request->Category['category3'])->first()->file;
        session(['input_category' => $request->Category]);
        return view('partials.materials_saved', compact('getBook'));
    }
}