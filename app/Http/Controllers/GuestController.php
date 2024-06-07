<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventParticipant;
use App\Models\Attendances;
use Illuminate\Support\Facades\Auth;

class GuestController extends Controller
{
    public function index()
    {
        $attendances = Attendances::where('access_code', Auth::user()->username)->first();

        $dataParticipant = EventParticipant::select('event_participant.*', 'attendances.event_name')->leftJoin('attendances', 'attendances.event_code', '=', 'event_participant.event_code')->where('attendances.access_code', Auth::user()->username)->latest();

        $itemsPerPage = 10;
        //print_r();
        // Menentukan jumlah halaman maksimum untuk semua data

        if ($itemsPerPage >= 10) {
            $totalPages = 10;
        }

        $dataParticipant = $dataParticipant->paginate($itemsPerPage);

        if ($dataParticipant->count() > 10) {
            $dataParticipant = $dataParticipant->paginate($itemsPerPage);

            if ($dataParticipant->currentPage() > $dataParticipant->lastPage()) {
                return redirect($dataParticipant->url($dataParticipant->lastPage()));
            }
        }

        return view('dashboard_guest', compact('dataParticipant', 'attendances'));
    }

    public function search(Request $request)
    {
        // Mendapatkan data pencarian dari request
        $searchData = $request->input('search');
        // Lakukan sesuatu dengan data pencarian, contoh: mencari data di database
        $name = $searchData['name'] ?? null;
        $email = $searchData['email'] ?? null;
        $phone_number = $searchData['phone_number'] ?? null;

        $attendances = Attendances::where('access_code', Auth::user()->username)->first();

        $dataParticipant = EventParticipant::select('event_participant.*', 'attendances.event_name')->leftJoin('attendances', 'attendances.event_code', '=', 'event_participant.event_code')->where('attendances.access_code', Auth::user()->username)->latest();

        $dataParticipant->where(function ($query) use ($name, $phone_number, $email) {
            if ($name !== null) {
                $query->where('name', 'like', "$name%");
            }

            if ($email !== null) {
                $query->where('email', 'like', "$email%");
            }

            if ($phone_number !== null) {
                $query->where('phone_number', 'like', "$phone_number%");
            }
        });

        $totaldataParticipant = $dataParticipant->count();
        //dd($searchData);
        // Menentukan jumlah item per halaman
        $itemsPerPage = 10;

        // Menentukan jumlah halaman maksimum untuk semua data
        $totalPagesAll = ceil($totaldataParticipant / $itemsPerPage);
        $dataParticipant = $dataParticipant->paginate($itemsPerPage);

        // Mendapatkan URI lengkap dari request
        $fullUri = $request->getRequestUri();

        if ($totalPagesAll >= 10) {
            $totalPages = 10;
        }

        $dataParticipant->setPath($fullUri);

        if ($dataParticipant->count() > 10) {
            $dataParticipant = $dataParticipant->paginate($itemsPerPage);
            //dd($dataParticipant);
            if ($dataParticipant->currentPage() > $dataParticipant->lastPage()) {
                return redirect($dataParticipant->url($dataParticipant->lastPage()));
            }
        }

        return view('dashboard_guest', compact('attendances', 'dataParticipant', 'searchData', 'itemsPerPage'));

    }
}