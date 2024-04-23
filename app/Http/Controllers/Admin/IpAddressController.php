<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IpLocked;
use Illuminate\Http\Request;
use App\Models\ListIP;

class IpAddressController extends Controller
{
    private $data;

    public function __construct()
    {
        $this->data['currentAdminMenu'] = 'authentication';
        $this->data['currentAdminSubMenu'] = 'ip_address';
        $this->data['currentTitle'] = 'IP Address | Artec Coding Indonesia';
    }

    public function index()
    {
        $listIP = ListIP::latest();
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
        //dd($getAll);
        return view('admin.ipAddress.index', $this->data, compact('listIP', 'ipBlocked', 'ipUnblocked'));
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
        $listIP = ListIP::latest();

        $listIP->where(function ($query) use ($network, $country_name, $netmask, $is_anonymous_proxy, $is_satellite_provider, $is_blocked) {
            if ($network !== null) {
                $query->where('network', 'like', "$network%");
            }

            if ($netmask !== null) {
                $query->where('netmask', $netmask);
            }

            if ($country_name !== null) {
                $query->where('country_name', $country_name);
            }

            if ($is_anonymous_proxy !== null) {
                $query->where('is_anonymous_proxy', $is_anonymous_proxy);
            }

            if ($is_satellite_provider !== null) {
                $query->where('is_satellite_provider', $is_satellite_provider);
            }

            if ($is_blocked !== null) {
                $query->whereDate('is_blocked', $is_blocked);
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

    public function blocked($id)
    {
        try {
            //dd(decrypt($id));
            $checkIp = ListIP::where('id', decrypt($id))->first();
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
}