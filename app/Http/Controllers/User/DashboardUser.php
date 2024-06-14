<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OnlineEvents;
use App\Models\Events;
use App\Models\EventParticipant;
use App\Models\Attendances;
use App\Models\User;
use App\Models\Notification;
use App\Repositories\ActivityRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

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
            ->get()
            ->map(function ($event) {
                $event->event_type = 'offline'; // Tandai sebagai offline event
                return $event;
            });

        // Ambil semua data dari tabel online_events dan sesuaikan kolomnya
        $onlineEvents = OnlineEvents::select(
            'online_events.name as nama_event',
            'online_events.event_date',
            'online_events.*',
            DB::raw('CASE WHEN event_participant.email IS NOT NULL THEN TRUE ELSE FALSE END as register')
        )
            ->leftJoin('event_participant', function ($join) use ($userEmail) {
                $join->on('event_participant.event_code', '=', 'online_events.code')
                    ->where('event_participant.email', '=', $userEmail);
            })->get()
            ->map(function ($event) {
                $event->event_type = 'online'; // Tandai sebagai online event
                return $event;
            });

        // Gabungkan kedua koleksi data menjadi satu koleksi
        $allEvents = $allEvents->concat($onlineEvents);

        $ev_participant = EventParticipant::select('event_code')->where('email', Auth::user()->email)->groupBy('event_code', 'email')->get()->pluck('event_code');
        //dd($ev_participant);
        $checkPresensi = Attendances::whereIn('event_code', $ev_participant)->get();

        foreach ($checkPresensi as $presensi) {
            if ($presensi->opening_date <= now() && $presensi->closing_date > now()) {
                $checkNotif = Notification::where('event_code', $presensi->event_code)->first();

                if (!$checkNotif) {
                    $newNotif = Notification::create([
                        'user_id' => Auth::user()->id,
                        'title' => 'Presensi Kehadiran Dibuka',
                        'content' => 'Presensi kehadiran atas event "' . $presensi->event_name . '" telah dibuka, silakan melakukan presensi hingga ' . $presensi->closing_date,
                        'event_code' => $presensi->event_code,
                        'redirect' => ''
                    ]);
                    Notification::where('id', $newNotif->id)->update([
                        'redirect' => env('APP_URL') . '/' . Auth::user()->role . '/detail-notification/' . $newNotif->id,
                    ]);
                }

            } else {
                Notification::where('event_code', $presensi->event_code)->forceDelete();
            }
        }

        $notifications = Notification::where('user_id', Auth::user()->id)
            ->orderBy('read', 'asc')
            ->orderBy('created_at', 'desc')->get();

        // Hitung jumlah notifikasi yang belum dibaca
        $totalNotification = $notifications->count();

        session([
            'info_notif' => [
                'total_notif' => $totalNotification,
                'notifications' => $notifications,
            ]
        ]);

        return view('user.dashboard', compact('allEvents', 'participants', 'totalPeserta', 'participantCounts', 'kotaTerbanyak', 'notifications', 'totalNotification'));

    }

    public function notificationView($id = null)
    {

        $notification = Notification::where('id', request()->route('id'))->first();

        $notification->update([
            'read' => true,
            'date_read' => now()
        ]);

        $notifications = Notification::where('user_id', Auth::user()->id)
            ->orderBy('read', 'asc')
            ->orderBy('created_at', 'desc')->get();

        // Hitung jumlah notifikasi yang belum dibaca
        $totalNotification = $notifications->count();

        Session::put('info_notif', [
            'total_notif' => $totalNotification,
            'notifications' => $notifications,
        ]);

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

        $presensi = Attendances::where('event_code', $notification->event_code)->first();

        if (now() > $presensi->closing_date) {
            $notification->update([
                'title' => 'Presensi Kehadiran Ditutup',
                'content' => 'Presensi kehadiran atas event "' . $presensi->event_name . '" telah ditutup sejak ' . $presensi->closing_date,
            ]);
        } else {
            $notification->update([
                'title' => 'Presensi Kehadiran Dibuka',
                'content' => 'Presensi kehadiran atas event "' . $presensi->event_name . '" telah dibuka, silakan melakukan presensi hingga ' . $presensi->closing_date,
            ]);

        }

        $myev = EventParticipant::where('event_code', $notification->event_code)->where('email', Auth::user()->email)->first();

        return view('user.dashboardNotification', compact('notification', 'totalPeserta', 'participantCounts', 'kotaTerbanyak', 'myev'));
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