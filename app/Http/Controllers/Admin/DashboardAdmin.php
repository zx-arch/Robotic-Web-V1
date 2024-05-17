<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\ActivityRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardAdmin extends Controller
{
    private $data;
    protected $activityRepository;

    public function __construct(ActivityRepositoryInterface $activityRepository)
    {
        $this->data['currentAdminMenu'] = 'dashboard';
        $this->data['currentTitle'] = 'Dashboard | Artec Coding Indonesia';
        $this->activityRepository = $activityRepository;
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('home_dashboard');

        } else {
            $accessData = $this->activityRepository->getAccessData();
            return view('admin.dashboard', $this->data, $accessData);
        }
    }

    public function search(Request $request)
    {
        $searchData = $request->input('search');
        $accessData = $this->activityRepository->searchAccessData($searchData);

        return view('admin.dashboard', $this->data, array_merge($accessData, compact('searchData')));
    }

}