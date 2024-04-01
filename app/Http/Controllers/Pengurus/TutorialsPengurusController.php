<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tutorials;
use App\Models\CategoryTutorial;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class TutorialsPengurusController extends Controller
{
    private $data;

    public function __construct()
    {
        $this->data['currentAdminMenu'] = 'tutorials';
    }

    public function index()
    {
        $tutorials = Tutorials::select('tutorials.*', 'category_tutorial.category as category_name')
            ->leftJoin('category_tutorial', 'category_tutorial.id', '=', 'tutorials.tutorial_category_id')
            ->with('masterStatus')->withTrashed()->latest();

        $getCategory = CategoryTutorial::all();
        //dd($getCategory);
        $totalTutorials = $tutorials->count();

        // Menentukan jumlah item per halaman
        $itemsPerPage = 10;
        //print_r();
        // Menentukan jumlah halaman maksimum untuk semua data
        $totalPagesAll = ceil($totalTutorials / $itemsPerPage);
        $tutorials = $tutorials->paginate($itemsPerPage);

        if ($totalPagesAll >= 15) {
            $totalPages = 15;
        }

        if ($tutorials->count() > 15) {
            $tutorials = $tutorials->paginate($itemsPerPage);
            //dd($tutorials);
            if ($tutorials->currentPage() > $tutorials->lastPage()) {
                return redirect($tutorials->url($tutorials->lastPage()));
            }
        }

        return view('pengurus.tutorials.index', $this->data, compact('tutorials', 'getCategory'));
    }
    public function search(Request $request)
    {
        // Mendapatkan data pencarian dari request
        $searchData = $request->input('search');
        // Lakukan sesuatu dengan data pencarian, contoh: mencari data di database
        $video_name = $searchData['video_name'] ?? null;
        $category = $searchData['category'] ?? null;
        $status_id = $searchData['status_id'] ?? null;
        $created_at = $searchData['created_at'] ?? null;
        $updated_at = $searchData['updated_at'] ?? null;
        $getCategory = CategoryTutorial::all();

        // Misalnya, Anda ingin mencari data user berdasarkan video_name, category, status_id, created_at, atau updated_at
        $tutorials = Tutorials::select('tutorials.*', 'category_tutorial.category as category_name')
            ->leftJoin('category_tutorial', 'category_tutorial.id', '=', 'tutorials.tutorial_category_id')
            ->withTrashed();

        $tutorials->where(function ($query) use ($video_name, $status_id, $category, $created_at, $updated_at) {
            if ($video_name !== null) {
                $query->where('tutorials.video_name', 'like', "$video_name%");
            }

            if ($status_id !== null) {
                $query->where('tutorials.status_id', $status_id);
            }

            if ($category !== null) {
                $query->where('tutorials.tutorial_category_id', $category);
            }

            if ($created_at !== null) {
                $query->where('tutorials.created_at', 'like', "$created_at%");
            }

            if ($updated_at !== null) {
                $query->where('tutorials.updated_at', 'like', "$updated_at%");
            }
        });


        $totaltutorials = $tutorials->count();
        //dd($searchData);
        // Menentukan jumlah item per halaman
        $itemsPerPage = 15;

        // Menentukan jumlah halaman maksimum untuk semua data
        $totalPagesAll = ceil($totaltutorials / $itemsPerPage);
        $tutorials = $tutorials->paginate($itemsPerPage);

        // Mendapatkan URI lengkap dari request
        $fullUri = $request->getRequestUri();

        if ($totalPagesAll >= 15) {
            $totalPages = 15;
        }

        $tutorials->setPath($fullUri);

        if ($tutorials->count() > 15) {
            $tutorials = $tutorials->paginate($itemsPerPage);
            //dd($tutorials);
            if ($tutorials->currentPage() > $tutorials->lastPage()) {
                return redirect($tutorials->url($tutorials->lastPage()));
            }
        }

        return view('pengurus.Tutorials.index', $this->data, compact('tutorials', 'searchData', 'itemsPerPage', 'getCategory'));

    }

    public function add()
    {
        $getCategory = Tutorials::select('category')->groupBy('category')->get()->pluck('category');

        return view('pengurus.tutorials.add', $this->data, compact('getCategory'));
    }

    public function saveTutorial(Request $request)
    {
        try {
            $validator = $this->validateWithBag('tutorial', $request, [
                'url_link' => ['required', 'url', 'regex:/^(https?:\/\/)?(www\.)?.+/'], // Aturan validasi untuk URL youtube
            ]);
            //dd($request->all());
            $imageData = $request->input('image');

            // Ambil ekstensi gambar dari data URI
            $imageExtension = explode('/', mime_content_type($imageData))[1];

            // Decode data base64 menjadi data biner
            $imageBinary = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));

            // Bangun nama file yang unik
            $uniqueImageName = 'image_thumb-' . $request->video_name . '_' . time() . '.' . $imageExtension;

            $tutorial = Tutorials::create([
                'video_name' => $request->video_name,
                'category' => $request->category,
                'thumbnail' => url('assets/youtube/' . $request->category . '/' . $uniqueImageName),
                'url' => $request->url_link,
                'path_video' => '-',
                'status_id' => 6,
            ]);

            // Jika data tutorial berhasil disimpan, lanjutkan dengan menyimpan file lokal
            if ($tutorial) {
                $directory = public_path('assets/youtube/' . $request->category);

                // Membuat direktori jika tidak ada
                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }

                // Simpan data image ke dalam file di direktori yang diinginkan
                file_put_contents(public_path('assets/youtube/' . $request->category . '/' . $uniqueImageName), $imageBinary);

                Activity::create(array_merge(session('myActivity'), [
                    'user_id' => Auth::user()->id,
                    'action' => Auth::user()->username . ' Add New Tutorial Video ID ' . $tutorial,
                ]));

                return redirect()->route('pengurus.tutorials.index')->with('success_submit_save', 'Data tutorial berhasil ditambah!');

            } else {
                // Jika data tutorial tidak berhasil disimpan, tampilkan pesan kesalahan atau lakukan penanganan kesalahan lainnya
                return redirect()->back()->with('error_submit_save', 'Gagal menyimpan data tutorial.');
            }

        } catch (ValidationException $e) {
            // Tangkap kesalahan validasi dan tampilkan pesan kesalahan kustom
            $errorMessage = 'URL yang anda masukkan tidak valid.';
            return redirect()->back()->with('error_submit_save', $errorMessage);

        } catch (\Throwable $e) {
            return redirect()->back()->with('error_submit_save', 'Gagal tambah data tutorial. ' . $e->getMessage());
        }

    }

    public function update($video_id)
    {
        try {
            $tutorial = Tutorials::where('id', decrypt($video_id))->first();
            $getCategory = Tutorials::select('category')->groupBy('category')->get()->pluck('category');

            return view('pengurus.Tutorials.update', $this->data, compact('tutorial', 'getCategory'));

        } catch (\Throwable $e) {
            return redirect()->route('pengurus.tutorials.index')->with('error_view', 'Halaman tidak tersedia, pastikan user terdaftar dan belum dihapus!' . $e->getMessage());
        }
    }

    public function saveUpdate($video_id, Request $request)
    {
        //dd($request->all(), $video_id);
        try {
            if ($request->isMethod('put')) {

                $video = Tutorials::find($video_id);

                if ($video) {

                    DB::transaction(function () use ($request, $video) {

                        if ($request->image != null) {
                            $imageData = $request->input('image');

                            // Ambil ekstensi gambar dari data URI
                            $imageExtension = explode('/', mime_content_type($imageData))[1];

                            // Decode data base64 menjadi data biner
                            $imageBinary = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));

                            // Bangun nama file yang unik
                            $uniqueImageName = 'image_thumb-' . $request->video_name . '_' . time() . '.' . $imageExtension;

                            $tutorial = Tutorials::where('id', $video->id)->update([
                                'video_name' => $request->video_name,
                                'category' => $request->category,
                                'status_id' => 6,
                                'url' => $request->url_link,
                                'path_video' => '-',
                                'thumbnail' => url('assets/youtube/' . $request->category . '/' . $uniqueImageName),
                            ]);

                            // Jika data tutorial berhasil disimpan, lanjutkan dengan menyimpan file lokal
                            if ($tutorial) {
                                $directory = public_path('assets/youtube/' . $request->category);

                                // Membuat direktori jika tidak ada
                                if (!file_exists($directory)) {
                                    mkdir($directory, 0777, true);
                                }
                            }
                            // Simpan data image ke dalam file di direktori yang diinginkan
                            file_put_contents(public_path('assets/youtube/' . $request->category . '/' . $uniqueImageName), $imageBinary);

                        }

                        Activity::create(array_merge(session('myActivity'), [
                            'user_id' => Auth::user()->id,
                            'action' => Auth::user()->username . ' Update Tutorial Video ID ' . $video->id,
                        ]));

                        Tutorials::where('id', $video->id)->update([
                            'video_name' => $request->video_name,
                            'category' => $request->category,
                            'path_video' => '-',
                            'status_id' => 6,
                            'url' => $request->url_link,
                        ]);
                    });

                    return redirect()->route('pengurus.tutorials.index')->with('success_submit_save', 'Data berhasil diupdate!');

                } else {
                    return redirect()->back()->with('error_submit_save', 'Video tutorial tidak tersedia!');
                }


            } else {
                return redirect()->back()->with('error_submit_save', 'Request data tidak valid!');
            }

        } catch (\Throwable $e) {
            return redirect()->back()->with('error_submit_save', 'Data gagal diupdate. ' . $e->getMessage());
        }
    }
}