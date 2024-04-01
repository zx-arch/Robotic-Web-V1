<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardAdmin extends Controller
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
            return view('admin.dashboard', $this->data);
        }
    }
}