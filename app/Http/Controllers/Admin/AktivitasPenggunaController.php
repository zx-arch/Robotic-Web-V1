<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Activity;

class AktivitasPenggunaController extends Controller
{
    private $data;
    public function __construct()
    {
        $this->data['currentAdminMenu'] = 'aktivitas_pengguna';
        $this->data['currentTitle'] = 'Aktivitas Pengguna';
    }
    public function index()
    {
        $activities = Activity::latest();
        $totalActivity = $activities->count();

        // Menentukan jumlah item per halaman
        $itemsPerPage = 15;
        //print_r();
        // Menentukan jumlah halaman maksimum untuk semua data
        $totalPagesAll = ceil($totalActivity / $itemsPerPage);
        $activities = $activities->paginate($itemsPerPage);

        if ($totalPagesAll >= 15) {
            $totalPages = 15;
        }

        if ($activities->count() > 15) {
            $activities = $activities->paginate($itemsPerPage);
            //dd($activities);
            if ($activities->currentPage() > $activities->lastPage()) {
                return redirect($activities->url($activities->lastPage()));
            }
        }
        return view('admin.AktivitasPengguna.index', $this->data, compact('activities'));
    }
}