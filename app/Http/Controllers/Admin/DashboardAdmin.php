<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\ActivityRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardAdmin extends Controller
{
    private $data;

    public function __construct()
    {
        $this->data['currentAdminMenu'] = 'dashboard';
        $this->data['currentTitle'] = 'Dashboard | Artec Coding Indonesia';
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('home_dashboard');

        } else {
            $accessData = ActivityRepository::getAccessData();
            return view('admin.dashboard', $this->data, $accessData);
        }
    }

    public function search(Request $request)
    {
        $searchData = $request->input('search');
        $accessData = ActivityRepository::searchAccessData($searchData);

        return view('admin.dashboard', $this->data, array_merge($accessData, compact('searchData')));
    }

}