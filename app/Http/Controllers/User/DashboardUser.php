<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Events;
use App\Models\EventParticipant;
use App\Models\Attendances;
use App\Models\User;
use App\Repositories\ActivityRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardUser extends Controller
{

    public function index()
    {
        if (session()->has('existingTables')) {
            session()->forget('existingTables');
        }

        $participants = EventParticipant::select('events.*', 'attendances.opening_date', 'attendances.closing_date', 'attendances.access_code', 'event_participant.id as id_user', 'event_participant.status_presensi', 'event_participant.waktu_presensi')->leftJoin('events', 'events.code', '=', 'event_participant.event_code')
            ->leftJoin('attendances', 'attendances.event_code', '=', 'events.code')->where('event_participant.email', Auth::user()->email)->get();

        // Menggabungkan dua query menjadi satu
        $participantStats = EventParticipant::select(
            DB::raw('COUNT(*) as total_peserta'),
            DB::raw('COUNT(CASE WHEN status_presensi = "Hadir" THEN 1 END) as hadir_count'),
            DB::raw('COUNT(CASE WHEN status_presensi = "Tidak Hadir" THEN 1 END) as tidak_hadir_count')
        )->first();

        // Mengambil kota dengan jumlah peserta terbanyak
        $kotaTerbanyak = Events::select('city', DB::raw('count(*) as total'))
            ->groupBy('city')
            ->orderBy('total', 'desc')
            ->first();

        $totalPeserta = $participantStats->total_peserta;
        $participantCounts = $participantStats;

        $userEmail = Auth::user()->email;

        // Query untuk mengambil data events untuk cek telah register belum
        $allEvents = Events::select(
            'events.*',
            'attendances.opening_date',
            'attendances.closing_date',
            'attendances.access_code',
            DB::raw('CASE WHEN event_participant.email IS NOT NULL THEN TRUE ELSE FALSE END as register')
        )
            ->leftJoin('event_participant', function ($join) use ($userEmail) {
                $join->on('event_participant.event_code', '=', 'events.code')
                    ->where('event_participant.email', '=', $userEmail);
            })
            ->leftJoin('attendances', 'attendances.event_code', '=', 'events.code')
            ->latest()
            ->get();

        return view('user.dashboard', compact('allEvents', 'participants', 'totalPeserta', 'participantCounts', 'kotaTerbanyak'));

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

                    return redirect()->route('user.dashboard')->with('error_saved', 'Gagal presensi, id tidak terdaftar!');

                } else {
                    return redirect()->route('user.dashboard')->with('error_saved', 'Gagal presensi, waktu presensi telah berakhir!');
                }

            } else {
                return redirect()->route('user.dashboard')->with('error_saved', 'Gagal presensi, tidak boleh akses melalui URL!');
            }

        } catch (\Throwable $e) {
            return redirect()->route('user.dashboard')->with('error_saved', 'Gagal presensi: ' . $e->getMessage());
        }
    }

    public function eventRegister(Request $request, $code)
    {
        $user = Auth::user();

        $getUser = User::where('email', $user->email)->first();
        $checkParticipant = EventParticipant::where('email', $user->email);

        if ($checkParticipant->where('event_code', $code)->first()) {
            return redirect()->route('user.dashboard');
        }

        $existingParticipant = $checkParticipant->first();
        $phoneNumber = $existingParticipant ? $existingParticipant->phone_number : '(not set)';

        EventParticipant::create([
            'event_code' => $code,
            'name' => $getUser->username,
            'email' => $getUser->email,
            'phone_number' => $phoneNumber,
        ]);

        return redirect()->route('user.dashboard')->with('success_saved', 'Berhasil Registrasi Event!');
    }

}