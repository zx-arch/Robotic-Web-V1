<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardPengurus extends Controller
{
    private $data;
    public function __construct()
    {
        $this->data['currentAdminMenu'] = 'dashboard';
        $this->data['currentTitle'] = 'Dashboard';
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('home_dashboard');

        } else {
            return view('pengurus.dashboard', $this->data);
        }
    }
}