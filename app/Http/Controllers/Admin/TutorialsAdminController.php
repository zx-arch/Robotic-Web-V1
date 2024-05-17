<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tutorials;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\ActivityRepositoryInterface;
use App\Models\CategoryTutorial;

class TutorialsAdminController extends Controller
{
    private $data;
    protected $activityRepository;

    public function __construct(ActivityRepositoryInterface $activityRepository)
    {
        $this->data['currentAdminMenu'] = 'tutorials';
        $this->data['currentAdminSubMenu'] = 'preview';
        $this->data['currentTitle'] = 'Preview Tutorials | Artec Coding Indonesia';
        $this->activityRepository = $activityRepository;
    }

    public function index()
    {
        $tutorials = Tutorials::select('tutorials.*', 'category_tutorial.category as category_name', 'master_status.name as status_name')->leftJoin('category_tutorial', 'category_tutorial.id', '=', 'tutorials.tutorial_category_id')
            ->leftJoin('master_status', 'master_status.id', '=', 'tutorials.status_id')
            ->with('masterStatus')->withTrashed()->latest();

        $deletedTutorialsCount = Tutorials::onlyTrashed()->count();
        //dd($tutorials->where('tutorials.id', 3)->first());
        //dd($deletedTutorialsCount);
        // $tutorials->where('tutorials.id', 3)->update([
        //     'thumbnail' => url('/assets/youtube/software requirement/Instalasi USB Driver For Windows 2.png'),
        // ]);

        $getCategory = CategoryTutorial::select('category_tutorial.*', 'master_status.name as status_name')
            ->leftJoin('master_status', 'master_status.id', '=', 'category_tutorial.status_id')
            ->with('masterStatus')->latest()->get();

        //dd($getCategory);

        //dd($tutorials->get());

        // Menentukan jumlah item per halaman
        $itemsPerPage = 10;

        // Menentukan jumlah halaman maksimum untuk semua data
        $totalPagesAll = $itemsPerPage;
        $tutorials = $tutorials->paginate($itemsPerPage);

        if ($totalPagesAll >= 15) {
            $totalPages = 15;
        }

        if ($tutorials->count() > 15) {
            $tutorials = $tutorials->paginate($itemsPerPage);
            if ($tutorials->currentPage() > $tutorials->lastPage()) {
                return redirect($tutorials->url($tutorials->lastPage()));
            }
        }

        return view('admin.Tutorials.index', $this->data, compact('tutorials', 'getCategory'));
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
        //dd($request->all());

        // Misalnya ingin mencari data user berdasarkan video_name, category, status_id, created_at, atau updated_at
        $tutorials = Tutorials::select('tutorials.*', 'category_tutorial.category as category_name', 'master_status.name as status_name')->leftJoin('category_tutorial', 'category_tutorial.id', '=', 'tutorials.tutorial_category_id')
            ->leftJoin('master_status', 'master_status.id', '=', 'tutorials.status_id')
            ->with('masterStatus')->withTrashed()->latest();

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

        return view('admin.Tutorials.index', $this->data, compact('tutorials', 'searchData', 'itemsPerPage', 'getCategory'));

    }
    public function add()
    {
        $getCategory = CategoryTutorial::all();

        return view('admin.Tutorials.add', $this->data, compact('getCategory'));
    }

    public function saveTutorial(Request $request)
    {
        //dd($request->all());
        try {
            $validator = Validator::make($request->all(), [
                'url_link' => ['required', 'url', 'regex:/^(https?:\/\/)?(www\.)?.+/'], // Aturan validasi untuk URL youtube
                'image' => ['required', 'image', 'mimes:jpeg,png', 'max:500'], // Aturan validasi untuk image
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);

            } else {

                $file = $request->file('image');

                // Dapatkan ekstensi file
                $imageExtension = $file->getClientOriginalExtension();

                // Buat nama unik untuk file gambar
                $uniqueImageName = 'image_thumb-' . $request->video_name . '_' . time() . '.' . $imageExtension;

                $tutorial = Tutorials::create([
                    'video_name' => $request->video_name,
                    'tutorial_category_id' => $request->category,
                    'thumbnail' => url('assets/youtube/' . CategoryTutorial::where('id', $request->category)->first()->category . '/' . $uniqueImageName),
                    'url' => $request->url_link,
                    'path_video' => '-',
                    'status_id' => ($request->status === 'enable') ? 4 : (($request->status === 'disable') ? 5 : 6),
                ]);

                // Jika data tutorial berhasil disimpan, lanjutkan dengan menyimpan file lokal
                if ($tutorial) {
                    $directory = public_path('assets/youtube/' . CategoryTutorial::where('id', $request->category)->first()->category);

                    // Membuat direktori jika tidak ada
                    if (!file_exists($directory)) {
                        mkdir($directory, 0777, true);
                    }

                    // Simpan data image ke dalam file di direktori yang diinginkan
                    $request->file('image')->move(public_path('assets/youtube/' . CategoryTutorial::where('id', $request->category)->first()->category), $uniqueImageName);

                    CategoryTutorial::where('id', $request->category)->update([
                        'valid_deleted' => false,
                        'delete_html_code' => '',
                    ]);

                    $this->activityRepository->create([
                        'user_id' => Auth::user()->id,
                        'action' => Auth::user()->username . ' Created Tutorial ID ' . $tutorial->id,
                    ]);

                    return redirect()->route('tutorials.index')->with('success_submit_save', 'Data tutorial berhasil ditambah!');

                } else {
                    // Jika data tutorial tidak berhasil disimpan, tampilkan pesan kesalahan atau lakukan penanganan kesalahan lainnya
                    return redirect()->back()->with('error_submit_save', 'Gagal menyimpan data tutorial.');
                }
            }

        } catch (ValidationException $e) {

            $errorMessage = '';

            if ($e->validator->errors()->has('url_link')) {
                $errorMessage = 'URL yang Anda masukkan tidak valid.';

            } elseif ($e->validator->errors()->has('image')) {
                $errorMessage = 'File gambar tidak valid. Pastikan tipe file adalah JPEG atau PNG dan ukuran file tidak lebih dari 500 KB.';
            }

            return redirect()->back()->with('error_submit_save', $errorMessage);

        } catch (\Throwable $e) {
            return redirect()->back()->with('error_submit_save', 'Gagal tambah data tutorial. ' . $e->getMessage());
        }

    }

    public function delete($video_id)
    {
        //dd($user_id);
        try {
            $video = Tutorials::find(decrypt($video_id));
            $deletedTutorialsCount = Tutorials::where('tutorial_category_id', $video->id)->onlyTrashed()->count();
            //dd($deletedTutorialsCount);
            if (isset($video)) {

                DB::transaction(function () use ($video) {

                    Tutorials::where('id', $video->id)->update([
                        'status_id' => 5
                    ]);

                    Tutorials::where('id', $video->id)->delete();

                    $this->activityRepository->create([
                        'user_id' => Auth::user()->id,
                        'action' => Auth::user()->username . ' Delete Tutorial Video ID ' . $video->id,
                    ]);

                });
                $deletedTutorialsCount = Tutorials::where('tutorial_category_id', $video->tutorial_category_id)->onlyTrashed()->count();
                $totalTutorialsCount = Tutorials::where('tutorial_category_id', $video->tutorial_category_id)->withTrashed()->count();
                //dd($deletedTutorialsCount, $totalTutorialsCount);
                if ($deletedTutorialsCount == $totalTutorialsCount) {
                    CategoryTutorial::where('id', $video->tutorial_category_id)->update([
                        'valid_deleted' => true,
                        'delete_html_code' => '<a class="btn btn-danger btn-sm btn-delete" href="' . route("category_tutorial.delete", ["id_cat" => encrypt($video->tutorial_category_id)]) . '"><i class="fa-fw fas fa-trash" aria-hidden></i></a>',
                    ]);
                }
                return redirect()->route('tutorials.index')->with('success_deleted', 'Data berhasil dihapus!');

            } else {
                return redirect()->route('tutorials.index')->with('error_deleted', 'ID tutorial tidak ditemukan .. data gagal dihapus!');
            }

        } catch (\Throwable $e) {
            return redirect()->back()->with('error_deleted', 'Data gagal dihapus. ' . $e->getMessage());
        }
    }

    public function update($video_id)
    {
        try {
            $tutorial = Tutorials::where('id', decrypt($video_id))->first();
            $getCategory = CategoryTutorial::all();

            return view('admin.Tutorials.update', $this->data, compact('tutorial', 'getCategory'));

        } catch (\Throwable $e) {
            return redirect()->route('tutorials.index')->with('error_view', 'Halaman tidak tersedia, pastikan user terdaftar dan belum dihapus!' . $e->getMessage());
        }
    }

    public function restore($video_id)
    {
        try {
            $data = Tutorials::withTrashed()->find(decrypt($video_id));

            DB::transaction(function () use ($data) {
                $data->restore();
                $data->update(['status_id' => 4]);

                $this->activityRepository->create([
                    'user_id' => Auth::user()->id,
                    'action' => Auth::user()->username . ' Restore Tutorial Video ID ' . $data->id,
                ]);

                CategoryTutorial::where('id', Tutorials::where('tutorial_category_id', $data->tutorial_category_id)->first()->tutorial_category_id)->update([
                    'valid_deleted' => false,
                    'delete_html_code' => '',
                ]);
            });

            return redirect()->route('tutorials.index')->with('success_restore', 'Data berhasil direstore!');

        } catch (\Throwable $e) {
            return redirect()->route('tutorials.index')->with('error_restore', 'User id tidak ditemukan, pastikan user sudah di delete!');
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
                            $file = $request->file('image');

                            // Dapatkan ekstensi file
                            $imageExtension = $file->getClientOriginalExtension();

                            // Buat nama unik untuk file gambar
                            $uniqueImageName = 'image_thumb-' . $request->video_name . '_' . time() . '.' . $imageExtension;

                            $tutorial = Tutorials::where('id', $video->id)->update([
                                'video_name' => $request->video_name,
                                'tutorial_category_id' => $request->category,
                                'status_id' => ($request->status === 'enable') ? 4 : (($request->status === 'disable') ? 5 : 6),
                                'url' => $request->url_link,
                                'path_video' => '-',
                                'thumbnail' => url('assets/youtube/' . CategoryTutorial::where('id', $request->category)->first()->category . '/' . $uniqueImageName),
                            ]);

                            // Jika data tutorial berhasil disimpan, lanjutkan dengan menyimpan file lokal
                            if ($tutorial) {
                                $directory = public_path('assets/youtube/' . CategoryTutorial::where('id', $request->category)->first()->category);

                                // Membuat direktori jika tidak ada
                                if (!file_exists($directory)) {
                                    mkdir($directory, 0777, true);
                                }
                            }
                            // Simpan data image ke dalam file di direktori yang diinginkan
                            $request->file('image')->move(public_path('assets/youtube/' . CategoryTutorial::where('id', $request->category)->first()->category), $uniqueImageName);

                        }

                        Tutorials::where('id', $video->id)->update([
                            'video_name' => $request->video_name,
                            'tutorial_category_id' => $request->category,
                            'path_video' => '-',
                            'status_id' => ($request->status === 'enable') ? 4 : (($request->status === 'disable') ? 5 : 6),
                            'url' => $request->url_link,
                        ]);

                        $this->activityRepository->create([
                            'user_id' => Auth::user()->id,
                            'action' => Auth::user()->username . ' Update Tutorial Video ID ' . $video->id,
                        ]);

                    });

                    return redirect()->route('tutorials.index')->with('success_submit_save', 'Data berhasil diupdate!');

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