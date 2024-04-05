<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Translations;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class LanguageController extends Controller
{
    private $data;
    public function __construct()
    {
        $this->data['currentAdminMenu'] = 'language';
    }
    public function index()
    {
        $translation = Translations::with('hierarchyCategoryBook')->orderBy('created_at', 'desc');
        //dd($translation);
        $totalUsers = $translation->count();

        // Menentukan jumlah item per halaman
        $itemsPerPage = 15;
        //print_r();
        // Menentukan jumlah halaman maksimum untuk semua data
        $totalPagesAll = ceil($totalUsers / $itemsPerPage);
        $translation = $translation->paginate($itemsPerPage);
        $fullUri = '/admin/language_translate';

        if ($totalPagesAll >= 15) {
            $totalPages = 15;
        }

        if ($translation->count() > 15) {
            $translation = $translation->paginate($itemsPerPage);
            //dd($translation);
            if ($translation->currentPage() > $translation->lastPage()) {
                return redirect($translation->url($translation->lastPage()));
            }
        }

        return view('admin.Language.index', $this->data, compact('translation', 'fullUri', 'itemsPerPage'));
    }

    public function search(Request $request)
    {
        // Mendapatkan data pencarian dari request
        $searchData = $request->input('search');
        // Lakukan sesuatu dengan data pencarian, contoh: mencari data di database
        $languageCode = $searchData['language_code'] ?? null;
        $languageName = $searchData['language_name'] ?? null;

        // Misalnya ingin mencari data bahasa berdasarkan Code dan/atau nama
        $translation = Translations::query();

        if ($languageCode !== null) {
            $translation->where('language_code', $languageCode);
        }

        if ($languageName !== null) {
            // Menggunakan where untuk menambahkan kondisi pencarian tambahan
            $translation->where('language_name', 'like', "$languageName%");
        }

        // Menentukan jumlah item per halaman
        $itemsPerPage = 1;

        // Lakukan paginasi data
        $translation = $translation->paginate($itemsPerPage);

        // Mendapatkan URI lengkap dari request
        $fullUri = $request->getRequestUri();

        // Mengambil parameter pencarian dari request
        $queryParams = $request->query('search');

        // Menambahkan parameter pencarian ke URL paginasi
        $translation->setPath($fullUri);
        //dd($translation);
        // Mengirimkan data ke view
        return view('admin.Language.index', $this->data, compact('translation', 'searchData', 'itemsPerPage'));
    }

    public function add()
    {
        return view('admin.Language.add', $this->data);
    }

    public function saveLanguage(Request $request)
    {
        //dd($request->all());
        try {
            $findTranslation = Translations::where('language_name', $request->language_name)->first();

            if (!$findTranslation) {
                Translations::create([
                    'language_code' => $request->language_code,
                    'language_name' => $request->language_name,
                ]);

                // Activity::create(array_merge(session('myActivity'), [
                //     'user_id' => Auth::user()->id,
                //     'action' => 'Admin Created a New Translation Language',
                // ]));

                return redirect()->route('language.index')->with('success_add_language', 'Terjemahan berhasil ditambah!');

            } else {
                return redirect()->back()->with('error_add_language', 'Terjemahan sudah terdaftar!');
            }

        } catch (\Throwable $e) {
            return redirect()->back()->with('error_add_language', 'Gagal tambah terjemahan! ' . $e->getMessage());
        }
    }
}