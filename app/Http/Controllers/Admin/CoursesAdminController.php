<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HierarchyCategoryBook;
use Illuminate\Http\Request;
use App\Models\BookTranslation;
use App\Models\Translations;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CoursesAdminController extends Controller
{
    private $data;

    public function __construct()
    {
        $this->data['currentAdminMenu'] = 'courses';
    }
    public function index()
    {
        // $bookTranslations = BookTranslation::select('book_translation.*', 'hierarchy_category_book.hierarchy_name')->leftJoin('hierarchy_category_book', 'hierarchy_category_book.id', '=', 'book_translation.hierarchy_id')
        //     ->with('hierarchyCategoryBook')->get();
        //dd($bookTranslations);
        $bookTranslations = BookTranslation::with(['hierarchyCategoryBook', 'hierarchyCategoryBook.parentCategory'])->withTrashed()->latest();

        $totalCatBookTranslations = $bookTranslations->count();

        // Menentukan jumlah item per halaman
        $itemsPerPage = 15;
        //print_r();
        // Menentukan jumlah halaman maksimum untuk semua data
        $totalPagesAll = ceil($totalCatBookTranslations / $itemsPerPage);
        $bookTranslations = $bookTranslations->paginate($itemsPerPage);

        if ($totalPagesAll >= 15) {
            $totalPages = 15;
        }

        if ($bookTranslations->count() > 15) {
            $bookTranslations = $bookTranslations->paginate($itemsPerPage);
            //dd($bookTranslations);
            if ($bookTranslations->currentPage() > $bookTranslations->lastPage()) {
                return redirect($bookTranslations->url($bookTranslations->lastPage()));
            }
        }
        return view('admin.Courses.index', $this->data, compact('bookTranslations'));
    }

    public function search(Request $request)
    {
        $searchData = $request->input('search');
        // Lakukan sesuatu dengan data pencarian, contoh: mencari data di database
        $book_title = $searchData['book_title'] ?? null;
        $terjemahan = $searchData['terjemahan'] ?? null;
        $status = $searchData['status'] ?? null;
        $parent = $searchData['parent'] ?? null;
        $created_at = $searchData['created_at'] ?? null;
        $updated_at = $searchData['updated_at'] ?? null;
        //dd($request->all());

        // Misalnya ingin mencari data user berdasarkan book_title, terjemahan, status, created_at, atau updated_at
        $bookTranslations = BookTranslation::with(['hierarchyCategoryBook', 'hierarchyCategoryBook.parentCategory'])->withTrashed()
            ->withTrashed()->latest();

        $bookTranslations->where(function ($query) use ($book_title, $status, $terjemahan, $parent, $created_at, $updated_at) {
            if ($book_title !== null) {
                $query->where('book_title', 'like', "%$book_title%");
            }

            if ($terjemahan !== null) {
                $query->where('language_id', $terjemahan);
            }

            if ($status !== null) {
                $query->where('status_id', $status);
            }

            if ($parent !== null) {
                $query->whereHas('hierarchyCategoryBook', function ($subquery) use ($parent) {
                    $subquery->where('hierarchy_name', 'like', "%$parent%");
                });
            }

            if ($created_at !== null) {
                $query->whereDate('created_at', $created_at);
            }

            if ($updated_at !== null) {
                $query->whereDate('updated_at', $updated_at);
            }
        });

        $totalbookTranslations = $bookTranslations->count();
        //dd($searchData);
        // Menentukan jumlah item per halaman
        $itemsPerPage = 15;

        // Menentukan jumlah halaman maksimum untuk semua data
        $totalPagesAll = ceil($totalbookTranslations / $itemsPerPage);
        $bookTranslations = $bookTranslations->paginate($itemsPerPage);

        // Mendapatkan URI lengkap dari request
        $fullUri = $request->getRequestUri();

        if ($totalPagesAll >= 15) {
            $totalPages = 15;
        }

        $bookTranslations->setPath($fullUri);

        if ($bookTranslations->count() > 15) {
            $bookTranslations = $bookTranslations->paginate($itemsPerPage);
            //dd($bookTranslations);
            if ($bookTranslations->currentPage() > $bookTranslations->lastPage()) {
                return redirect($bookTranslations->url($bookTranslations->lastPage()));
            }
        }

        return view('admin.Courses.index', $this->data, compact('bookTranslations', 'searchData', 'itemsPerPage'));

    }

    public function add()
    {
        $getTerjemahan = Translations::all();
        return view('admin.Courses.add', $this->data, compact('getTerjemahan'));
    }

    public function saveCourses(Request $request)
    {
        //dd($request->all());
        try {
            $validator = Validator::make($request->all(), [
                'ebook_file' => ['required', 'file', 'mimes:pdf', 'max:50000'], // Aturan validasi untuk image
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);

            } else {

                $file = $request->file('ebook_file');

                // Dapatkan ekstensi file
                $imageExtension = $file->getClientOriginalExtension();

                // Buat nama unik untuk file gambar
                $uniqueImageName = $request->book_title . '.' . $imageExtension;

                HierarchyCategoryBook::create([
                    'name' => $request->book_title,
                    'hierarchy_name' => $request->book_title,
                    'language_id' => 7,
                    'parent_id' => 35,
                ]);

                return redirect()->route('admin.courses.index')->with('success_submit_save', 'Data courses berhasil ditambah!');
            }

        } catch (ValidationException $e) {

            $errorMessage = '';

            if ($e->validator->errors()->has('ebook_file')) {
                $errorMessage = 'File tidak valid. Pastikan tipe file adalah PDF dan ukuran file tidak lebih dari 50 MB.';
            }

            return redirect()->back()->with('error_submit_save', $errorMessage);

        } catch (\Throwable $e) {
            return redirect()->back()->with('error_submit_save', 'Gagal tambah data courses. ' . $e->getMessage());
        }
    }
}