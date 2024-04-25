<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IpGlobal;
use App\Models\IpLocked;

class IpGlobalController extends Controller
{
    private $data;

    public function __construct()
    {
        $this->data['currentAdminMenu'] = 'authentication';
        $this->data['currentAdminSubMenu'] = 'ip_global';
        $this->data['currentTitle'] = 'IP Global | Artec Coding Indonesia';
    }
    public function index()
    {
        $publicIp = IpGlobal::leftJoin('ip_locked', function ($join) {
            $join->on('ip_global.network', '=', 'ip_locked.network');
        })
            ->select('ip_global.*')
            ->selectRaw('IF(ip_locked.network IS NULL, false, true) as is_locked')
            ->latest();
        // Menentukan jumlah item per halaman
        $itemsPerPage = 15;
        //print_r();
        // Menentukan jumlah halaman maksimum untuk semua data
        $publicIp = $publicIp->paginate($itemsPerPage);

        if ($itemsPerPage >= 15) {
            $totalPages = 15;
        }

        if ($publicIp->count() > 15) {
            if ($publicIp->currentPage() > $publicIp->lastPage()) {
                return redirect($publicIp->url($publicIp->lastPage()));
            }
        }

        $ipBlocked = IpGlobal::where('is_blocked', 1)->get()->count();
        $ipUnblocked = IpGlobal::where('is_blocked', 0)->get()->count();

        return view('admin.IpGlobal.index', $this->data, compact('publicIp', 'ipBlocked', 'ipUnblocked'));
    }

    public function search(Request $request)
    {
        $searchData = $request->input('search');
        // Lakukan sesuatu dengan data pencarian, contoh: mencari data di database
        $network = $searchData['network'] ?? null;
        $netmask = $searchData['netmask'] ?? null;
        $country_name = $searchData['country_name'] ?? null;
        $is_anonymous_proxy = $searchData['is_anonymous_proxy'] ?? null;
        $is_satellite_provider = $searchData['is_satellite_provider'] ?? null;
        $is_blocked = $searchData['is_blocked'] ?? null;
        //dd($request->all());

        // Misalnya ingin mencari data ip berdasarkan network, netmask, country_name, is_satellite_provider, atau is_blocked
        $publicIp = IpGlobal::leftJoin('ip_locked', function ($join) {
            $join->on('ip_global.network', '=', 'ip_locked.network');
        })
            ->select('ip_global.*')
            ->selectRaw('IF(ip_locked.network IS NULL, false, true) as is_locked')
            ->latest();

        $publicIp->where(function ($query) use ($network, $country_name, $netmask, $is_anonymous_proxy, $is_satellite_provider, $is_blocked) {
            if ($network !== null) {
                $query->where('ip_global.network', 'like', "$network%");
            }

            if ($netmask !== null) {
                $query->where('ip_global.netmask', $netmask);
            }

            if ($country_name !== null) {
                $query->where('ip_global.country_name', $country_name);
            }

            if ($is_anonymous_proxy !== null) {
                $query->where('ip_global.is_anonymous_proxy', $is_anonymous_proxy);
            }

            if ($is_satellite_provider !== null) {
                $query->where('ip_global.is_satellite_provider', $is_satellite_provider);
            }

            if ($is_blocked !== null) {
                $query->where('ip_global.is_blocked', $is_blocked);
            }
        });

        //dd($searchData);
        $itemsPerPage = 15;
        //print_r();
        // Menentukan jumlah halaman maksimum untuk semua data
        $publicIp = $publicIp->paginate($itemsPerPage);

        if ($itemsPerPage >= 15) {
            $totalPages = 15;
        }

        $fullUri = $request->getRequestUri();

        $publicIp->setPath($fullUri);

        if ($publicIp->count() > 15) {
            if ($publicIp->currentPage() > $publicIp->lastPage()) {
                return redirect($publicIp->url($publicIp->lastPage()));
            }
        }

        $ipBlocked = $publicIp->where('is_blocked', 1)->count();
        $ipUnblocked = $publicIp->where('is_blocked', 0)->count();

        return view('admin.IpGlobal.index', $this->data, compact('publicIp', 'searchData', 'ipBlocked', 'ipUnblocked'));
    }


    public function blocked($id)
    {
        try {
            //dd(decrypt($id));
            $checkIp = IpGlobal::where('id', decrypt($id))->first();
            $checkLock = IpLocked::where('network', $checkIp->network)->first();

            if (!$checkLock) {

                $checkIp->update([
                    'is_blocked' => true,
                ]);

                return redirect()->back()->with('success_blocked', 'IP berhasil diblock, user dengan ip tersebut tidak dapat login!');

            } else {
                return redirect()->back()->with('error_blocked', 'IP tidak dapat diblock karena sudah di-lock!');
            }

        } catch (\Throwable $e) {
            return redirect()->back()->with('error_blocked', 'IP berhasil diblock. ' . $e->getMessage());
        }
    }

    public function locked($id)
    {
        try {
            $findIp = IpGlobal::where('id', decrypt($id))->first();
            $checkLock = IpLocked::where('id', decrypt($id))->first();

            if ($findIp) {
                if (!$checkLock) {

                    $findIp->update([
                        'is_blocked' => false,
                    ]);

                    IpLocked::where('id', decrypt($id))->create([
                        'network' => $findIp->network,
                    ]);

                    return redirect()->back()->with('success_locked', 'IP berhasil dilock, IP tidak dapat dihapus!');

                } else {
                    return redirect()->back()->with('error_locked', 'IP sudah di-lock!');
                }
            }

        } catch (\Throwable $e) {
            return redirect()->back()->with('error_locked', 'IP gagal dilock. ' . $e->getMessage());
        }
    }

    public function unlocked($id)
    {
        try {
            $findIp = IpGlobal::where('id', decrypt($id))->first();
            $checkLock = IpLocked::where('id', decrypt($id))->first();

            if ($findIp) {
                if (!$checkLock) {

                    $findIp->update([
                        'is_blocked' => false,
                    ]);

                    return redirect()->back()->with('success_unlocked', 'IP berhasil dilock, IP tidak dapat dihapus!');

                } else {
                    return redirect()->back()->with('error_unlocked', 'IP sudah di-lock!');
                }
            }

        } catch (\Throwable $e) {
            return redirect()->back()->with('error_unlocked', 'IP gagal dilock. ' . $e->getMessage());
        }
    }
}