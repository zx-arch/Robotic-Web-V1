<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IpLocked;
use Illuminate\Http\Request;
use App\Models\ListIP;

class IpLockedController extends Controller
{
    private $data;

    public function __construct()
    {
        $this->data['currentAdminMenu'] = 'authentication';
        $this->data['currentAdminSubMenu'] = 'ip_locked';
        $this->data['currentTitle'] = 'IP Locked | Artec Coding Indonesia';
    }

    public function index()
    {
        $listIP = IpLocked::select(
            'ip_locked.id',
            'list_ip.network',
            'list_ip.netmask',
            'list_ip.country_name',
            'list_ip.is_satellite_provider',
            'list_ip.is_blocked',
            'list_ip.created_at',
            'list_ip.updated_at',
        )->leftJoin('list_ip', 'list_ip.network', '=', 'ip_locked.network')
            ->latest();
        // Menentukan jumlah item per halaman
        $itemsPerPage = 15;
        //print_r();
        // Menentukan jumlah halaman maksimum untuk semua data
        $listIP = $listIP->paginate($itemsPerPage);

        if ($itemsPerPage >= 15) {
            $totalPages = 15;
        }

        if ($listIP->count() > 15) {
            if ($listIP->currentPage() > $listIP->lastPage()) {
                return redirect($listIP->url($listIP->lastPage()));
            }
        }

        $ipBlocked = ListIP::where('is_blocked', 1)->get()->count();
        $ipUnblocked = ListIP::where('is_blocked', 0)->get()->count();

        return view('admin.IpAddress.locked', $this->data, compact('listIP', 'ipBlocked', 'ipUnblocked'));
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
        $listIP = IpLocked::select(
            'ip_locked.id',
            'list_ip.network',
            'list_ip.netmask',
            'list_ip.country_name',
            'list_ip.is_satellite_provider',
            'list_ip.is_blocked',
            'list_ip.created_at',
            'list_ip.updated_at',
        )->leftJoin('list_ip', 'list_ip.network', '=', 'ip_locked.network')
            ->latest();

        $listIP->where(function ($query) use ($network, $country_name, $netmask, $is_anonymous_proxy, $is_satellite_provider, $is_blocked) {
            if ($network !== null) {
                $query->where('list_ip.network', 'like', "$network%");
            }

            if ($netmask !== null) {
                $query->where('list_ip.netmask', $netmask);
            }

            if ($country_name !== null) {
                $query->where('list_ip.country_name', $country_name);
            }

            if ($is_anonymous_proxy !== null) {
                $query->where('list_ip.is_anonymous_proxy', $is_anonymous_proxy);
            }

            if ($is_satellite_provider !== null) {
                $query->where('list_ip.is_satellite_provider', $is_satellite_provider);
            }

            if ($is_blocked !== null) {
                $query->whereDate('list_ip.is_blocked', $is_blocked);
            }
        });

        //dd($searchData);
        $itemsPerPage = 15;
        //print_r();
        // Menentukan jumlah halaman maksimum untuk semua data
        $listIP = $listIP->paginate($itemsPerPage);

        if ($itemsPerPage >= 15) {
            $totalPages = 15;
        }

        $fullUri = $request->getRequestUri();

        $listIP->setPath($fullUri);

        if ($listIP->count() > 15) {
            if ($listIP->currentPage() > $listIP->lastPage()) {
                return redirect($listIP->url($listIP->lastPage()));
            }
        }

        $ipBlocked = ListIP::where('is_blocked', 1)->get()->count();
        $ipUnblocked = ListIP::where('is_blocked', 0)->get()->count();

        return view('admin.ipAddress.index', $this->data, compact('listIP', 'searchData', 'ipBlocked', 'ipUnblocked'));

    }

    public function saveLocked($id)
    {
        try {
            $findIp = ListIP::where('id', decrypt($id))->first();

            if ($findIp) {
                IpLocked::where('id', decrypt($id))->create([
                    'network' => $findIp->network,
                ]);

                return redirect()->back()->with('success_locked', 'IP berhasil dilock, IP tidak dapat dihapus!');
            }

        } catch (\Throwable $e) {
            return redirect()->back()->with('error_locked', 'IP gagal dilock. ' . $e->getMessage());
        }

    }

    public function saveUnlocked($id)
    {
        try {
            $findIp = IpLocked::where('id', decrypt($id))->first();

            if ($findIp) {
                IpLocked::where('id', decrypt($id))->delete();
                return redirect()->back()->with('success_unlocked', 'IP success unlocked!');

            } else {
                return redirect()->back()->with('error_unlocked', 'Invalid ID IP address!');
            }

        } catch (\Throwable $e) {
            return redirect()->back()->with('error_unlocked', 'IP error unlocked! ' . $e->getMessage());
        }

    }
}