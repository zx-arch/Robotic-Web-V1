<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;

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

            $accessPercentageByIP = Activity::accessPercentageByIP();
            $accessByCity = $accessPercentageByIP->first();

            $countActivity = $accessPercentageByIP->sum('total_access');

            $totalAccessDevice = count($accessPercentageByIP); // Menghitung jumlah alamat IP unik

            $highestAccess = $accessPercentageByIP->max('access_percentage'); // Menghitung presentase akses tertinggi
            //dd($highestAccess);
            return view('admin.dashboard', $this->data, compact('accessPercentageByIP', 'countActivity', 'totalAccessDevice', 'accessByCity', 'highestAccess'));
        }
    }

    public function search(Request $request)
    {
        // Mendapatkan data pencarian dari request
        $searchData = $request->input('search');
        // Lakukan sesuatu dengan data pencarian, contoh: mencari data di database
        $ip_address = $searchData['ip_address'] ?? null;
        $district = $searchData['district'] ?? null;
        $province = $searchData['province'] ?? null;
        $country = $searchData['country'] ?? null;

        $query = Activity::query();

        // Menambahkan kondisi pencarian
        if ($ip_address) {
            $query->where('ip_address', 'like', "$ip_address%");
        }
        if ($district) {
            $query->where('city', 'like', "$district%");
        }
        if ($province) {
            $query->where('city', 'like', "%$province%");
        }
        if ($country) {
            $query->where('country', 'like', "$country%");
        }

        $accessCounts = $query->select('ip_address', 'country', 'city')
            ->selectRaw('count(*) as access_count')
            ->groupBy('ip_address', 'country', 'city')
            ->orderBy('access_count', 'desc')
            ->get();

        // Hitung total akses
        $totalAccess = $accessCounts->sum('access_count');

        // Hitung presentase untuk setiap alamat IP
        $accessPercentageByIP = $accessCounts->map(function ($item) use ($totalAccess) {
            return [
                'ip_address' => $item->ip_address,
                'city' => $item->city,
                'country' => $item->country,
                'access_percentage' => ($item->access_count / $totalAccess) * 100,
                'total_access' => $item->access_count,
            ];
        });

        $accessByCity = $accessPercentageByIP->first();

        $countActivity = $accessPercentageByIP->sum('total_access');

        $totalAccessDevice = count($accessPercentageByIP); // Menghitung jumlah alamat IP unik

        $highestAccess = $accessPercentageByIP->max('access_percentage'); // Menghitung presentase akses tertinggi

        return view('admin.dashboard', $this->data, compact('accessPercentageByIP', 'countActivity', 'searchData', 'totalAccessDevice', 'accessByCity', 'highestAccess'));
    }


}