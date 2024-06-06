<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventParticipant;
use App\Models\Attendances;
use App\Repositories\ActivityRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class DashboardUser extends Controller
{
    public function index()
    {
        try {
            $participants = EventParticipant::select('events.*', 'attendances.opening_date', 'attendances.closing_date', 'attendances.access_code', 'event_participant.id as id_user', 'event_participant.status_presensi', 'event_participant.waktu_presensi')->leftJoin('events', 'events.code', '=', 'event_participant.event_code')
                ->leftJoin('attendances', 'attendances.event_code', '=', 'events.code')->where('event_participant.email', Auth::user()->email)->get();

            return view('user.dashboard', compact('participants'));

        } catch (\Throwable $e) {

            Auth::logout();

            session()->flush();

            Cookie::queue(Cookie::forget('user_email'));

            return redirect()->intended('/login')->with('error', 'Data account sistem tidak valid!');
        }
    }
    public function presentUser(Request $request, $code, $id)
    {
        try {

            if ($request->isMethod('post')) {

                $checkTime = Attendances::where('event_code', $code)->first()->closing_date;

                if ($checkTime > now()) {
                    $checkUser = EventParticipant::where('id', decrypt($id))->first();

                    if ($checkUser) {
                        $checkUser->update([
                            'status_presensi' => 'Hadir',
                            'waktu_presensi' => now()
                        ]);

                        ActivityRepository::create([
                            'user_id' => Auth::user()->id,
                            'action' => Auth::user()->username . ' Presensi Kehadiran Event Code ' . $code,
                        ]);

                        return redirect()->route('user.dashboard')->with('success_saved', 'Berhasil melakukan presensi!');
                    }

                    return redirect()->route('user.dashboard')->with('error_saved', 'Gagal presensikan peserta, id peserta tidak terdaftar!');

                } else {
                    return redirect()->route('user.dashboard')->with('error_saved', 'Gagal presensikan peserta, waktu presensi telah berakhir!');
                }
            } else {
                return redirect()->route('user.dashboard')->with('error_saved', 'Gagal presensikan peserta, tidak boleh akses melalui URL!');
            }

        } catch (\Throwable $e) {
            return redirect()->route('user.dashboard')->with('error_saved', 'Gagal presensikan peserta: ' . $e->getMessage());
        }
    }
}