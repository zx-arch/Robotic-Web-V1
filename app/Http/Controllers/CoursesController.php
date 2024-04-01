<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HierarchyCategoryBook;
use App\Models\BookTranslation;
use App\Models\Translations;
use App\Models\Levels;
use Illuminate\Support\Facades\DB;

class CoursesController extends Controller
{
    private $data;

    public function __construct()
    {
        $this->data['currentActive'] = 'courses_by_id';
    }
    public function index($jenis_materi)
    {
        //dd($jenis_materi);
        session(['jenis_materi' => $jenis_materi]);
        $subCatMateri = HierarchyCategoryBook::where('parent_id', 0)
            ->where('hierarchy_name', 'like', $jenis_materi . '%')->first()->id;
        $subCatMateri = HierarchyCategoryBook::where('parent_id', $subCatMateri)->get()->pluck('id');
        //dd($subCatMateri);
        if ($jenis_materi == 'block_programming' || $jenis_materi == 'python_programming') {
            // $getLanguage = BookTranslation::select('book_translation.language_id', 'translations.language_name')
            //     ->leftJoin('translations', 'translations.id', '=', 'book_translation.language_id')
            //     ->with('translations')
            //     ->groupBy('book_translation.language_id', 'translations.language_name')
            //     ->get();

            $getLevels = HierarchyCategoryBook::select('hierarchy_category_book.*', 'translations.language_name')
                ->leftJoin('translations', 'translations.id', '=', 'hierarchy_category_book.language_id')
                ->whereIn('hierarchy_category_book.id', $subCatMateri)
                ->get();

            $getLanguages = HierarchyCategoryBook::select('hierarchy_category_book.language_id', DB::raw('MAX(translations.language_name) as language_name'))
                ->leftJoin('translations', 'translations.id', '=', 'hierarchy_category_book.language_id')
                ->whereIn('hierarchy_category_book.id', $subCatMateri)
                ->distinct()
                ->groupBy('hierarchy_category_book.language_id')
                ->get();

            //dd($getLanguages);
            session(['getLevels' => $getLevels, 'getLanguages' => $getLanguages]);
            return redirect()->back()->with('valid_book', true);

        } elseif ($jenis_materi == 'ai_programming') {

            $getLanguages = HierarchyCategoryBook::select('hierarchy_category_book.language_id', DB::raw('MAX(translations.language_name) as language_name'))
                ->leftJoin('translations', 'translations.id', '=', 'hierarchy_category_book.language_id')
                ->whereIn('hierarchy_category_book.id', $subCatMateri)
                ->distinct()
                ->groupBy('hierarchy_category_book.language_id')
                ->get();

            $getIDCatAI = HierarchyCategoryBook::where('parent_id', 0)->where('name', 'like', $jenis_materi . '%')->first()->id;
            $getChapter = Levels::whereIn('hierarchy_id', HierarchyCategoryBook::select('id')->where('parent_id', $getIDCatAI)->get()->pluck('id'))->get();
            //dd($getChapter);
            session(['getChapter' => $getChapter, 'getLanguages' => $getLanguages]);
            return redirect()->back()->with('valid_book', true);

        } else {
            return redirect()->back()->with('error_access_book', 'Materi tidak tersedia!');
        }
    }

    public function submitBook(Request $request)
    {
        try {
            if ($request->isMethod('POST')) {
                //dd(session('getLevels'), $request->all());
                if (!$request->has('chapter')) {
                    //dd($request->all());
                    if ($request->has('level')) {
                        $getIDHierarchy = HierarchyCategoryBook::select('id')
                            ->where('hierarchy_category_book.parent_id', $request->level)
                            ->where('hierarchy_category_book.language_id', $request->terjemahan)->get()->pluck('id');

                        $getBook = BookTranslation::whereIn('hierarchy_id', $getIDHierarchy)->where('status_id', true)
                            ->get();

                    } else {
                        $subCatIDMateri = HierarchyCategoryBook::where('parent_id', 0)->where('hierarchy_name', 'like', $request->jenis_materi . '%')->first()->id;
                        $getBook = BookTranslation::where('language_id', $request->terjemahan)
                            ->whereIn('hierarchy_id', HierarchyCategoryBook::where('parent_id', $subCatIDMateri)->get()->pluck('id'))->where('status_id', true)
                            ->get();
                        //dd($getBook, $request->all());

                        $getIDHierarchy = $getBook;
                    }

                } else {

                    //dd($request->all());
                    $getIDHierarchy = HierarchyCategoryBook::select('id')
                        ->where('hierarchy_category_book.id', $request->chapter)
                        ->where('hierarchy_category_book.language_id', $request->terjemahan)->get()->pluck('id');

                    $getBook = BookTranslation::whereIn('hierarchy_id', $getIDHierarchy)->where('status_id', true)
                        ->get();
                }

                //dd($request->all(), $getIDHierarchy, $getBook);
                if (count($getIDHierarchy) == 0) {
                    session([
                        'not_available_book' =>
                            ($request->has('level')) ? 'Versi buku terjemahan ' .
                            Translations::where('id', $request->terjemahan)->first()->language_name . ' level ' . HierarchyCategoryBook::where('id', $request->level)->first()->name . ' saat ini belum tersedia' : 'Versi buku terjemahan ' .
                            Translations::where('id', $request->terjemahan)->first()->language_name . ' saat ini belum tersedia',
                        'request' => $request->all()
                    ]);

                } else {
                    session(['getBook' => $getBook, 'request_input_book' => $request->all()]);
                }

                return redirect()->back()->with('valid_book', true);

            } else {
                return redirect()->back()->with('error_access_book', 'Request data tidak valid!');
            }

        } catch (\Throwable $e) {
            session()->forget('valid');
            //dd($e->getMessage());
            return redirect()->back()->with('error_access_book', 'Request data tidak valid. ' . $e->getMessage());
        }
    }
}