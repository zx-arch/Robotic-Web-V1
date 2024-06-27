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
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DashboardUser extends Controller
{

    public function index()
    {
        if (session()->has('existingTables')) {
            session()->forget('existingTables');
        }

        $userEmail = Auth::user()->email;

        $participants = EventParticipant::select(
            'events.*',
            'events.nama_event as event_offline',
            'online_events.event_date as event_date_online',
            'online_events.name as event_online',
            'attendances.opening_date',
            'attendances.closing_date',
            'attendances.access_code',
            'event_participant.id as id_user',
            'event_participant.status_presensi',
            'event_participant.waktu_presensi'
        )
            ->leftJoin('events', 'events.code', '=', 'event_participant.event_code')
            ->leftJoin('online_events', 'online_events.code', '=', 'event_participant.event_code')
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
        $eventCodes = $allEvents->pluck('code')->toArray();

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
        $onlineEventCodes = $onlineEvents->pluck('code')->toArray();

        // For Events table
        Attendances::whereIn('event_code', $eventCodes)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('events')
                    ->whereColumn('events.code', 'attendances.event_code')
                    ->whereColumn('events.nama_event', '<>', 'attendances.event_name'); // Adjust this condition as needed
            })
            ->update([
                'attendances.event_name' => DB::raw('(SELECT nama_event FROM events WHERE events.code = attendances.event_code)'),
                'attendances.updated_at' => now(), // Update the updated_at timestamp
            ]);

        // For OnlineEvents table
        Attendances::whereIn('event_code', $onlineEventCodes)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('online_events')
                    ->whereColumn('online_events.code', 'attendances.event_code')
                    ->whereColumn('online_events.name', '<>', 'attendances.event_name'); // Adjust this condition as needed
            })
            ->update([
                'attendances.event_name' => DB::raw('(SELECT name FROM online_events WHERE online_events.code = attendances.event_code)'),
                'attendances.updated_at' => now(), // Update the updated_at timestamp
            ]);

        // Gabungkan kedua koleksi data menjadi satu koleksi
        $allEvents = $allEvents->concat($onlineEvents);

        // $myev = $allEvents->pluck('code');

        // $email = Auth::user()->email;

        // // Query untuk mendapatkan attendances
        // $attendances = Attendances::whereIn('event_code', function ($query) use ($email) {
        //     $query->select('event_code')
        //         ->from('event_participant')
        //         ->where('email', $email);
        // })->where('opening_date', '<=', now())->where('closing_date', '>', now())->whereNull('deleted_at')->get();

        // // Query untuk mendapatkan notifikasi terkait
        // $checkNotif = Notification::select('notification.*')->leftJoin('attendances', 'attendances.event_code', '=', 'notification.event_code')
        //     ->whereIn('notification.event_code', function ($query) use ($email) {
        //         $query->select('event_code')
        //             ->from('event_participant')
        //             ->where('email', $email);
        //     });

        // if ($attendances->isNotEmpty()) {
        //     foreach ($attendances as $presensi) {
        //         $newNotif = Notification::create([
        //             'user_id' => Auth::user()->id,
        //             'title' => 'Presensi Kehadiran Dibuka',
        //             'content' => 'Presensi kehadiran atas event "' . $presensi->event_name . '" telah dibuka, silakan melakukan presensi hingga ' . $presensi->closing_date,
        //             'event_code' => $presensi->event_code,
        //             'redirect' => ''
        //         ]);

        //         $newNotif->update([
        //             'redirect' => env('APP_URL') . '/' . Auth::user()->role . '/detail-notification/' . $newNotif->id,
        //         ]);
        //     }
        // } else {
        //     $checkNotif->where('attendances.closing_date', '<=', now())->forceDelete();
        // }

        // $evNotifCodes = $checkNotif->pluck('event_code')->toArray();

        // foreach ($onlineEvents as $event) {
        //     if (!in_array($event->code, $evNotifCodes)) {

        //         if ($event->link_online) {
        //             $eventDate = Carbon::parse($event->event_date);
        //             $content = "Anda telah menerima link zoom dalam acara '{$event->nama_event}' yang diselenggarakan pada : <br><br>
        //             <span class=\"text-primary fw-bold\">Hari / Tanggal</span>&nbsp;&nbsp;:&nbsp; {$eventDate->isoFormat('dddd, D MMMM YYYY')}<br>
        //             <span class=\"text-primary fw-bold\">Pukul</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class=\"ms-5\">:&nbsp; {$eventDate->isoFormat('HH:mm')}</span> <br>
        //             <span class=\"text-primary fw-bold\">Pembicara</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp; {$event->speakers} <br>
        //             <br><a href='{$event->link_online}'>{$event->link_online}</a>";

        //             if ($event->user_access && $event->passcode) {
        //                 $content .= "<br><br>ID Access : {$event->user_access}<br>Passcode: {$event->passcode}";
        //             }

        //             $newNotif = Notification::create([
        //                 'user_id' => Auth::user()->id,
        //                 'title' => 'New Link Zoom Invitation',
        //                 'content' => $content,
        //                 'event_code' => $event->code,
        //                 'link_online' => $event->link_online,
        //                 'id_access' => $event->user_access,
        //                 'passcode' => $event->passcode,
        //                 'redirect' => ''
        //             ]);

        //             $newNotif->update([
        //                 'redirect' => env('APP_URL') . '/' . Auth::user()->role . '/detail-notification/' . $newNotif->id,
        //             ]);

        //             $notif[] = $event->code;

        //         }
        //     }
        // }

        return view('user.dashboard', compact('allEvents', 'participants', 'totalPeserta', 'participantCounts', 'kotaTerbanyak'));

    }

    public function notificationView($id = null)
    {

        $notification = Notification::where('id', request()->route('id'))->first();

        $notification->update([
            'read' => true,
            'date_read' => now()
        ]);


        $onlineEvents = OnlineEvents::leftJoin('event_participant', 'event_participant.event_code', '=', 'online_events.code')->where('email', Auth::user()->email)->whereNotNull('online_events.link_online')->get();

        if ($onlineEvents) {
            foreach ($onlineEvents as $event) {
                $eventDate = Carbon::parse($event->event_date);

                if ($eventDate->diffInDays(Carbon::now()) > 1) {
                    Notification::where('event_code', $event->code)->forceDelete();
                }
            }
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

        $presensi = Attendances::where('event_code', $notification->event_code)->where('status', 'Enable')->first();

        if ($presensi) {
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

        }

        $notifications = Notification::where('user_id', Auth::user()->id)
            ->orderBy('read', 'asc')
            ->orderBy('created_at', 'desc')->get();

        // Hitung jumlah notifikasi yang belum dibaca
        $totalNotification = $notifications->count();

        Session::put('info_notif', [
            'total_notif' => $totalNotification,
            'notifications' => $notifications,
        ]);

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
                    if ($checkTime->status != 'Enable') {
                        return redirect()->route('user.dashboard')->with('error_saved', 'Presensi telah dinonaktifkan!');
                    }

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