<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HierarchyCategoryBook;
use Illuminate\Http\Request;
use App\Models\BookTranslation;
use App\Models\Translations;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;

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
        $bookTranslations = BookTranslation::select('book_translation.*', 'master_status.name as status')->leftJoin('master_status', 'master_status.id', '=', 'book_translation.status_id')->with(['hierarchyCategoryBook', 'hierarchyCategoryBook.parentCategory', 'masterStatus', 'translations'])->withTrashed()->latest();
        $getAvailableLanguage = BookTranslation::select('language_id', 'language_name')->groupBy('language_id', 'language_name')->with('translations')->get();

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
        return view('admin.Courses.index', $this->data, compact('bookTranslations', 'getAvailableLanguage'));
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
        $availableIdTerjemahan = HierarchyCategoryBook::select('language_id')->with('bookTranslations')->groupBy('language_id')->get()->pluck('language_id');
        $availableTerjemahan = Translations::whereIn('id', $availableIdTerjemahan)->with('hierarchyCategoryBook')->get();
        $allHierarchy = HierarchyCategoryBook::with('bookTranslations')->get();
        //dd(session()->all());
        //dd($allHierarchy);
        return view('admin.Courses.add', $this->data, compact('allHierarchy', 'availableTerjemahan'));
    }

    public function infoCourses(Request $request)
    {
        if (isset($request->courses['level'])) {
            session([
                'courses' => [
                    'terjemahan' => Translations::where('id', $request->courses['terjemahan'])->first()->language_name,
                    'parent' => HierarchyCategoryBook::where('id', $request->courses['parent'])->first()->name,
                    'level' => (isset($request->courses['level']) ? HierarchyCategoryBook::where('id', $request->courses['level'])->first()->name : ''),
                ],
                'courses_asli' => $request->courses,
            ]);
        }

        if (isset($request->courses['chapter'])) {
            session([
                'courses' => [
                    'terjemahan' => Translations::where('id', $request->courses['terjemahan'])->first()->language_name,
                    'parent' => HierarchyCategoryBook::where('id', $request->courses['parent'])->first()->name,
                    'chapter'(isset($request->courses['chapter']) ? HierarchyCategoryBook::where('id', $request->courses['chapter'])->first()->name : ''),
                ],
                'courses_asli' => $request->courses,
            ]);
        }
        //dd(session('courses'), session('courses_asli'));
        return $this->add();
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
                $checkLanguage = HierarchyCategoryBook::where('language_id', session('courses_asli')['terjemahan'])->get();

                if (session()->has('courses_asli') && session()->has('courses')) {
                    if ($checkLanguage->count() > 0) {

                        $file = $request->file('ebook_file');

                        // Dapatkan ekstensi file
                        $imageExtension = $file->getClientOriginalExtension();

                        // Buat nama unik untuk file gambar
                        $uniqueImageName = $request->book_title . '.' . $imageExtension;

                        if (isset(session('courses_asli')['level'])) {
                            $newHierarchy = HierarchyCategoryBook::create([
                                'name' => $request->book_title,
                                'hierarchy_name' => HierarchyCategoryBook::where('id', session('courses_asli')['level'])->first()->hierarchy_name . ' > ' . $request->book_title,
                                'language_id' => session('courses_asli')['terjemahan'],
                                'parent_id' => session('courses_asli')['level'],
                            ]);

                            $book = BookTranslation::create([
                                'book_title' => $request->book_title,
                                'language_id' => session('courses_asli')['terjemahan'],
                                'language_name' => session('courses')['terjemahan'],
                                'pages' => $request->pages,
                                'status_id' => $request->status_id,
                                'hierarchy_id' => $newHierarchy->id,
                                'file' => $uniqueImageName,
                            ]);

                        } else {

                            $newHierarchy = HierarchyCategoryBook::create([
                                'name' => $request->book_title,
                                'hierarchy_name' => HierarchyCategoryBook::where('id', session('courses')['parent'])->first()->hierarchy_name . ' > ' . $request->book_title,
                                'language_id' => session('courses_asli')['terjemahan'],
                                'parent_id' => session('courses_asli')['parent'],
                            ]);

                            $book = BookTranslation::create([
                                'book_title' => $request->book_title,
                                'language_id' => session('courses_asli')['terjemahan'],
                                'language_name' => session('courses')['terjemahan'],
                                'pages' => $request->pages,
                                'status_id' => $request->status_id,
                                'hierarchy_id' => $newHierarchy->id,
                                'file' => $uniqueImageName,
                            ]);
                        }

                        session(['success_submit_save' => 'berhasil simpan data!']);

                        Activity::create(array_merge(session('myActivity'), [
                            'user_id' => Auth::user()->id,
                            'action' => Auth::user()->username . ' Add Courses ' . $request->book_title,
                        ]));

                        // Jika data tutorial berhasil disimpan, lanjutkan dengan menyimpan file lokal
                        if ($book) {
                            $directory = public_path('book/' . session('courses')['terjemahan']);

                            // Membuat direktori jika tidak ada
                            if (!file_exists($directory)) {
                                mkdir($directory, 0777, true);
                            }

                            // Simpan data image ke dalam file di direktori yang diinginkan
                            $request->file('ebook_file')->move(public_path('book/' . session('courses')['terjemahan']), $uniqueImageName);

                        }

                        return response()->json(['message' => 'success']);

                    } else {
                        // tampilan muncul di halaman add courses
                        return response()->json(['message' => 'Tambahkan terjemahan bahasa di hierarchy category']);
                    }

                } else {
                    // tampilan muncul di halaman add courses
                    return response()->json(['message' => 'Belum memasukkan info courses!']);
                }
            }

        } catch (ValidationException $e) {

            $errorMessage = '';

            if ($e->validator->errors()->has('ebook_file')) {
                $errorMessage = 'File tidak valid. Pastikan tipe file adalah PDF dan ukuran file tidak lebih dari 50 MB.';
            }

            session(['error_submit_save' => $errorMessage]);

            return response()->json(['message' => 'failed']);

        } catch (\Throwable $e) {

            session(['error_submit_save' => 'Gagal simpan data! ' . $e->getMessage()]);

            return response()->json(['message' => 'failed']);
        }
    }

    public function update($id)
    {
        $detailCourses = BookTranslation::select('book_translation.*', 'hierarchy_category_book.hierarchy_name')
            ->leftJoin('hierarchy_category_book', 'hierarchy_category_book.id', '=', 'book_translation.hierarchy_id')
            ->with('hierarchyCategoryBook')
            ->where('book_translation.id', $id)->first();

        return view('admin.Courses.update', $this->data, compact('detailCourses'));
    }

    public function forceDelete($id)
    {
        //dd(decrypt($id));
        try {
            $detailCourses = BookTranslation::where('id', decrypt($id))->first();
            $findHierarchy = HierarchyCategoryBook::where('id', $detailCourses->hierarchy_id)->first();

            if (isset($detailCourses) && isset($findHierarchy)) {

                DB::transaction(function () use ($detailCourses, $findHierarchy) {
                    $findHierarchy->forceDelete();
                    $detailCourses->forceDelete();

                    // Hapus file-file dari penyimpanan
                    $path = public_path('book/' . $detailCourses->language_name . '/' . $detailCourses->file);
                    if (file_exists($path)) {
                        unlink($path);
                    }
                });

            } else {
                return redirect()->route('admin.courses.index')->with('error_submit_save', 'Data gagal di delete, ID tidak ditemukan!');
            }

            return redirect()->route('admin.courses.index')->with('success_submit_save', 'Data berhasil di delete!');

        } catch (\Throwable $e) {
            return redirect()->route('admin.courses.index')->with('error_submit_save', 'Data gagal di delete! ' . $e->getMessage());
        }
    }
}