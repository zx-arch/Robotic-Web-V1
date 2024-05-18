<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\ActivityRepository;
use Illuminate\Http\Request;
use App\Models\Activity;

class AktivitasPenggunaController extends Controller
{
    private $data;
    public function __construct()
    {
        $this->data['currentAdminMenu'] = 'aktivitas_pengguna';
        $this->data['currentTitle'] = 'Aktivitas Pengguna | Artec Coding Indonesia';
    }
    public function index()
    {
        $activities = ActivityRepository::setPaginate();

        return view('admin.AktivitasPengguna.index', $this->data, compact('activities'));
    }
}