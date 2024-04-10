<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategoryTutorial;
use App\Models\Tutorials;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Activity;

class CategoryTutorialController extends Controller
{
    private $data;

    public function __construct()
    {
        $this->data['currentAdminMenu'] = 'tutorials';
        $this->data['currentAdminSubMenu'] = 'category_tutorial';
    }

    public function index()
    {
        $categoryTutorial = CategoryTutorial::latest();
        $totalCatTutorials = $categoryTutorial->count();

        // Menentukan jumlah item per halaman
        $itemsPerPage = 15;
        //print_r();
        // Menentukan jumlah halaman maksimum untuk semua data
        $totalPagesAll = ceil($totalCatTutorials / $itemsPerPage);
        $categoryTutorial = $categoryTutorial->paginate($itemsPerPage);

        if ($totalPagesAll >= 15) {
            $totalPages = 15;
        }

        $getCategory = $categoryTutorial->all();

        if ($categoryTutorial->count() > 15) {
            $categoryTutorial = $categoryTutorial->paginate($itemsPerPage);
            //dd($categoryTutorial);
            if ($categoryTutorial->currentPage() > $categoryTutorial->lastPage()) {
                return redirect($categoryTutorial->url($categoryTutorial->lastPage()));
            }
        }

        return view('admin.CategoriesTutorial.index', $this->data, compact('categoryTutorial', 'getCategory'));
    }
    public function search(Request $request)
    {
        // Mendapatkan data pencarian dari request
        $searchData = $request->input('search');
        // Lakukan sesuatu dengan data pencarian, contoh: mencari data di database
        $category = $searchData['category'] ?? null;
        $status = $searchData['status'] ?? null;
        $created_at = $searchData['created_at'] ?? null;
        $updated_at = $searchData['updated_at'] ?? null;
        //dd($searchData);
        // Misalnya, Anda ingin mencari data user berdasarkan username, category, status, created_at, atau updated_at
        $categoryTutorial = CategoryTutorial::latest();
        $getCategory = $categoryTutorial->get();

        $categoryTutorial->where(function ($query) use ($status, $category, $created_at, $updated_at) {

            if ($status !== null) {
                $query->where('status_id', $status);
            }

            if ($category !== null) {
                $query->where('id', $category);
            }

            if ($created_at !== null) {
                $query->where('created_at', 'like', "$created_at%");
            }

            if ($updated_at !== null) {
                $query->Where('updated_at', 'like', "$updated_at%");
            }
        });

        $totalcategoryTutorial = $categoryTutorial->count();

        // Menentukan jumlah item per halaman
        $itemsPerPage = 15;

        // Menentukan jumlah halaman maksimum untuk semua data
        $totalPagesAll = ceil($totalcategoryTutorial / $itemsPerPage);
        $categoryTutorial = $categoryTutorial->paginate($itemsPerPage);

        // Mendapatkan URI lengkap dari request
        $fullUri = $request->getRequestUri();

        if ($totalPagesAll >= 15) {
            $totalPages = 15;
        }

        $categoryTutorial->setPath($fullUri);

        if ($categoryTutorial->count() > 15) {
            $categoryTutorial = $categoryTutorial->paginate($itemsPerPage);
            //dd($categoryTutorial);
            if ($categoryTutorial->currentPage() > $categoryTutorial->lastPage()) {
                return redirect($categoryTutorial->url($categoryTutorial->lastPage()));
            }
        }
        return view('admin.CategoriesTutorial.index', $this->data, compact('categoryTutorial', 'getCategory', 'searchData', 'itemsPerPage'));

    }
    public function addSubmit(Request $request)
    {
        //dd($request->all());
        try {
            if ($request->status_id != '11' || $request->status_id != '12') {

                DB::transaction(function () use ($request) {
                    $catTutorial = CategoryTutorial::create([
                        'category' => $request->category_name,
                        'status_id' => $request->status,
                        'valid_deleted' => true,
                        'delete_html_code' => '',
                    ]);

                    $catTutorial::where('id', $catTutorial->id)->update([
                        'delete_html_code' => '<a class="btn btn-danger btn-sm btn-delete" href="' . route("category_tutorial.delete", ["id_cat" => encrypt($catTutorial->id)]) . '"><i class="fa-fw fas fa-trash" aria-hidden></i></a>',
                    ]);

                    Activity::create(array_merge(session('myActivity'), [
                        'user_id' => Auth::user()->id,
                        'action' => Auth::user()->username . ' Add Categories ' . $request->category_name,
                    ]));
                });

                return redirect()->route('category_tutorial.index')->with('success_submit_save', 'Category berhasil ditambah!');

            } else {
                return redirect()->route('category_tutorial.index')->with('error_submit_save', 'Status ID tidak valid!');
            }

        } catch (\Throwable $e) {
            return redirect()->route('category_tutorial.index')->with('error_submit_save', 'Data gagal ditambah. ' . $e->getMessage());
        }

    }

    public function update($id_cat)
    {
        try {
            $categoryTutorial = CategoryTutorial::latest();
            $getCategory = $categoryTutorial->get();
            $findCat = CategoryTutorial::where('id', $id_cat)->first();

            if (isset($findCat)) {
                return view('admin.CategoriesTutorial.update', $this->data, compact('findCat', 'getCategory'));

            } else {
                return redirect()->route('category_tutorial.index')->with('error_find_cat', 'Category not found.');
            }

        } catch (\Throwable $e) {
            return redirect()->route('category_tutorial.index')->with('error_submit_save', 'Category not found. ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        //dd(decrypt($id));
        try {
            $findTutorial = Tutorials::where('tutorial_category_id', decrypt($id))->withTrashed();
            $getDeleteTutorial = Tutorials::where('tutorial_category_id', decrypt($id))->onlyTrashed();
            //dd($findTutorial, $getDeleteTutorial);

            if ($findTutorial->count() == $getDeleteTutorial->count()) {

                if ($findTutorial->count() > 0) {
                    $findTutorial::delete();
                }

                CategoryTutorial::where('id', decrypt($id))->delete();

                return redirect()->route('category_tutorial.index')->with('success_deleted', 'Data berhasil dihapus!');

            } else {
                return redirect()->route('category_tutorial.index')->with('error_deleted', 'Data tidak diizinkan dihapus, pastikan telah delete semua tutorial');
            }

        } catch (\Throwable $e) {
            return redirect()->route('category_tutorial.index')->with('error_deleted', 'Data gagal dihapus! ' . $e->getMessage());
        }

    }

    public function saveUpdate($id_cat, Request $request)
    {
        $findCat = CategoryTutorial::where('id', $id_cat)->first();

        //dd($id_cat, $request->all(), $findCat);

        if (isset($findCat)) {

            $arryUpdate = [];

            if ($findCat->category !== $request->category_name) {
                $arryUpdate['category'] = $request->category_name;
            }

            if ($findCat->status_id != $request->status) {
                $arryUpdate['status_id'] = $request->status;
            }

            if ($findCat->is_shown != $request->is_shown) {
                $arryUpdate['is_shown'] = $request->is_shown;
            }

            //dd($arryUpdate);

            if (isset($arryUpdate)) {

                CategoryTutorial::where('id', $id_cat)->update($arryUpdate);

                Activity::create(array_merge(session('myActivity'), [
                    'user_id' => Auth::user()->id,
                    'action' => Auth::user()->username . ' Update Categories ' . $findCat->category,
                ]));

                return redirect()->route('category_tutorial.index')->with('success_submit_save', 'Category berhasil diupdate!');

            } else {
                return redirect()->route('category_tutorial.index')->with('success_submit_save', 'Category berhasil diupdate!');
            }

        } else {
            return redirect()->route('category_tutorial.index')->with('error_find_cat', 'Category not found.');
        }
    }
}