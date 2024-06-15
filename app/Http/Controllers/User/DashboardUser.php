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

        $myev = $allEvents->pluck('code');

        $myEvent = EventParticipant::whereIn('event_code', $myev)->where('email', Auth::user()->email)->get()->pluck('event_code');

        $checkNotifs = Notification::whereIn('event_code', $myEvent)->get()->keyBy('event_code');

        // ambil data di daftar presensi sesuai event yang telah user daftar
        $checkPresensi = Attendances::whereIn('event_code', $myEvent)->where('status', 'Enable')->get();

        if ($checkPresensi) {
            foreach ($checkPresensi as $presensi) {

                if ($presensi->opening_date <= now() && $presensi->closing_date > now()) {

                    // cek apakah sudah masuk notifikasi
                    if (!$checkNotifs->has($presensi->event_code)) {
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
                    Notification::where('event_code', $presensi->event_code)->update([
                        'title' => 'Presensi Kehadiran Ditutup',
                        'content' => 'Presensi kehadiran atas event "' . $presensi->event_name . '" telah ditutup sejak ' . $presensi->closing_date,
                    ]);
                }
            }

        }
        // Ambil semua event_code yang sudah ada dalam notifikasi
        $notif = Notification::pluck('event_code')->toArray();

        // Ambil semua online events yang memiliki link online
        $onlineEvents = OnlineEvents::select('online_events.*')->leftJoin('event_participant', 'event_participant.event_code', '=', 'online_events.code')
            ->where('event_participant.email', Auth::user()->email)->whereNotNull('online_events.link_online')->get();

        if ($onlineEvents) {
            foreach ($onlineEvents as $event) {

                $eventDate = Carbon::parse($event->event_date);

                // Periksa apakah event_code belum ada dalam notifikasi
                if (!in_array($event->code, $notif)) {
                    $eventName = $event->name;
                    $day = $eventDate->isoFormat('dddd, D MMMM YYYY');
                    $time = $eventDate->isoFormat('HH:mm');
                    $speaker = $event->speakers;
                    $link = $event->link_online;
                    $idAccess = $event->user_access;
                    $passcode = $event->passcode;

                    // Bangun string HTML dengan interpolasi
                    $content = "Anda telah menerima link zoom dalam acara '$eventName' yang diselenggarakan pada : <br><br>
                    <span class=\"text-primary fw-bold\">Hari / Tanggal</span>&nbsp;&nbsp;:&nbsp; $day<br>
                    <span class=\"text-primary fw-bold\">Pukul</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class=\"ms-5\">:&nbsp; $time</span> <br>
                    <span class=\"text-primary fw-bold\">Pembicara</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp; $speaker <br>
                    <br><a href='$link'>$link</a>";

                    if ($idAccess && $passcode) {
                        $content += "<br><br>ID Access : $idAccess<br>Passcode: $passcode";
                    }

                    // Simpan notifikasi baru
                    $newNotif = Notification::create([
                        'user_id' => Auth::user()->id,
                        'title' => 'New Link Zoom Invitation',
                        'content' => $content,
                        'event_code' => $event->code,
                        'link_online' => $event->link_online,
                        'id_access' => $event->user_access,
                        'passode' => $event->passode,
                        'redirect' => ''
                    ]);

                    // Update field redirect
                    Notification::where('id', $newNotif->id)->update([
                        'redirect' => env('APP_URL') . '/' . Auth::user()->role . '/detail-notification/' . $newNotif->id,
                    ]);

                    // Tambahkan event_code ke array notifikasi untuk menghindari duplikasi dalam loop
                    $notif[] = $event->code;

                } else {

                    $eventName = $event->name;
                    $day = $eventDate->isoFormat('dddd, D MMMM YYYY');
                    $time = $eventDate->isoFormat('HH:mm');
                    $speaker = $event->speakers;
                    $link = $event->link_online;
                    $idAccess = $event->user_access;
                    $passcode = $event->passcode;

                    // Bangun string HTML dengan interpolasi
                    $content = "Anda telah menerima link zoom dalam acara '$eventName' yang diselenggarakan pada : <br><br>
                    <span class=\"text-primary fw-bold\">Hari / Tanggal</span>&nbsp;&nbsp;:&nbsp; $day<br>
                    <span class=\"text-primary fw-bold\">Pukul</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class=\"ms-5\">:&nbsp; $time</span> <br>
                    <span class=\"text-primary fw-bold\">Pembicara</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp; $speaker <br>
                    <br><a href='$link'>$link</a>";

                    if ($idAccess && $passcode) {
                        $content += "<br><br>ID Access : $idAccess<br>Passcode: $passcode";
                    }
                    Notification::where('event_code', $notif)->update([
                        'content' => $content,
                    ]);

                }
            }
        }

        $notifications = Notification::select('notification.*', 'online_events.event_date as event_date_online', 'events.event_date as event_date_offline')->leftJoin('online_events', 'online_events.code', '=', 'notification.event_code')
            ->leftJoin('events', 'events.code', '=', 'notification.event_code')
            ->where('notification.user_id', Auth::user()->id)
            ->orderBy('notification.read', 'asc')
            ->orderBy('notification.created_at', 'desc')->get();

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