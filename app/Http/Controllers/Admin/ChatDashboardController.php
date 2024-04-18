<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatDashboard;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;

class ChatDashboardController extends Controller
{
    private $data;

    public function __construct()
    {
        $this->data['currentAdminMenu'] = 'chat_dashboard';
    }

    public function index()
    {
        $chats = ChatDashboard::latest();

        session(['countChat' => $chats->get()->count()]);

        // Menentukan jumlah item per halaman
        $itemsPerPage = 15;
        //print_r();
        // Menentukan jumlah halaman maksimum untuk semua data
        $totalPagesAll = $itemsPerPage;
        $chats = $chats->paginate($itemsPerPage);

        if ($totalPagesAll >= 15) {
            $totalPages = 15;
        }

        if ($chats->count() > 15) {
            $chats = $chats->paginate($itemsPerPage);
            //dd($chats);
            if ($chats->currentPage() > $chats->lastPage()) {
                return redirect($chats->url($chats->lastPage()));
            }
        }
        return view('admin.ChatDashboard.index', $this->data, compact('chats'));
    }

    public function delete(Request $request)
    {
        try {
            $idsString = $request->input('delete_ids');

            // Pecah string menjadi array menggunakan koma sebagai delimiter
            $idsArray = explode(",", $idsString);

            // Validasi apakah setiap elemen dalam array adalah bilangan bulat
            $validIds = array_filter($idsArray, function ($id) {
                return is_numeric($id);
            });

            // Jika jumlah elemen dalam $validIds sama dengan jumlah elemen dalam $idsArray, maka validasi berhasil
            if (count($validIds) === count($idsArray)) {
                // dd($request->all(), explode(',', $request->delete_ids));
                ChatDashboard::whereIn('id', $idsArray)->forceDelete();

                Activity::create(array_merge(session('myActivity'), [
                    'user_id' => Auth::user()->id,
                    'action' => Auth::user()->username . ' Deleted Chat ID ' . $idsString,
                ]));

                return redirect()->back()->with('success_deleted', 'Chat berhasil dihapus!');

            } else {
                // Jika terdapat elemen dalam $idsArray yang tidak valid, kembalikan kembali dengan pesan kesalahan
                return redirect()->back()->with('error_deleted', 'Invalid input deleted detected');
            }

        } catch (\Throwable $e) {
            return redirect()->back()->with('error_deleted', 'Chat gagal dihapus! ' . $e->getMessage());
        }
    }
}