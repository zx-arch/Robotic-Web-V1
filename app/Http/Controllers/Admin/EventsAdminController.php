<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\ActivityRepository;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Attendances;
use App\Models\Events;
use App\Models\EventManager;
use App\Models\EventParticipant;
use App\Models\OnlineEvents;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class EventsAdminController extends Controller
{
    private $data;

    public function __construct()
    {
        $this->data['currentAdminMenu'] = 'events';
        $this->data['currentTitle'] = 'Events | Artec Coding Indonesia';

        $data = DB::table('information_schema.columns')
            ->select('column_name')
            ->where('table_name', 'events')
            ->get();

        $columns = $data->pluck('column_name')->toArray();

        if (!in_array('type_event', $columns)) {
            DB::statement('ALTER TABLE events ADD COLUMN type_event VARCHAR(50) NULL AFTER event_section');
        }
    }

    public function index()
    {
        $events = Events::select(
            'events.code as code_event',
            'events.nama_event',
            'events.location',
            'events.event_date',
            'events.organizer_name',
            'events.event_section',
            'events.poster',
            'events.created_at',
            DB::raw('(SELECT COUNT(*) FROM event_manager WHERE event_manager.event_code = events.code) as total_pengurus'),
            DB::raw('(SELECT COUNT(*) FROM event_participant WHERE event_participant.event_code = events.code) as total_peserta')
        )
            ->groupBy(
                'events.code',
                'events.nama_event',
                'events.location',
                'events.event_date',
                'events.organizer_name',
                'events.event_section',
                'events.poster',
                'events.created_at'
            )
            ->latest()
            ->get();

        $onlineEvents = OnlineEvents::get();
        $eventNotSetPresensi = Events::whereNotIn('code', Attendances::select('event_code')->get()->pluck('event_code'))->get();

        return view('admin.Events.index', $this->data, compact('events', 'eventNotSetPresensi', 'onlineEvents'));
    }

    public function listPresensi()
    {
        $listPresensiQuery = Attendances::select(
            'attendances.*',
            DB::raw('(SELECT COUNT(*) FROM event_participant WHERE event_participant.event_code = attendances.event_code) as total_peserta'),
            DB::raw('(SELECT COUNT(*) FROM event_participant WHERE event_participant.event_code = attendances.event_code and event_participant.status_presensi = "Hadir") as peserta_hadir'),
            DB::raw('(SELECT COUNT(*) FROM event_participant WHERE event_participant.event_code = attendances.event_code and event_participant.status_presensi = "Tidak Hadir") as peserta_tidak_hadir')
        )->groupBy(
                'attendances.id',
                'attendances.event_code',
                'attendances.event_name',
                'attendances.status',
                'attendances.opening_date',
                'attendances.closing_date',
                'attendances.access_code',
                'attendances.created_at',
                'attendances.updated_at',
                'attendances.deleted_at'
            );

        $listPresensi = $listPresensiQuery->get();

        $eventNotSetPresensi = Events::whereNotIn('code', $listPresensi->pluck('event_code'))->get();

        return view('admin.Events.listPresensi', $this->data, compact('listPresensi', 'eventNotSetPresensi'));
    }

    public function detailPresensi($code)
    {
        $participants = DB::table('event_participant')->where('event_code', $code)->latest();

        $itemsPerPage = 15;
        //print_r();
        // Menentukan jumlah halaman maksimum untuk semua data

        if ($itemsPerPage >= 15) {
            $totalPages = 15;
        }

        $participants = $participants->paginate($itemsPerPage);

        if ($participants->count() > 15) {
            $participants = $participants->paginate($itemsPerPage);

            if ($participants->currentPage() > $participants->lastPage()) {
                return redirect($participants->url($participants->lastPage()));
            }
        }

        $attendances = Attendances::where('event_code', $code)->first();
        $events = Events::where('code', $code)->first();

        return view('admin.Events.detailPresensi', $this->data, compact('code', 'participants', 'attendances', 'events'));
    }

    public function perpanjangPresensi(Request $request, $code)
    {
        //dd($code, $request->all());
        return redirect()->back()->with('click_perpanjang', true);
    }

    public function submitSetPresensi(Request $request, $code)
    {
        try {
            $validator = Validator::make($request->all(), [
                'setTgl' => 'required|date|after_or_equal:today',
                'waktuMulai' => 'required|date_format:H:i',
                'waktuBerakhir' => 'required|date_format:H:i|after:waktuMulai',
            ], [
                'setTgl.after_or_equal' => 'Tanggal presensi harus setidaknya hari ini.',
                'waktuBerakhir.after' => 'Jam berakhir harus lebih besar dari jam mulai.',
            ]);

            // Jika validasi gagal
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            Attendances::where('event_code', $code)->update([
                'opening_date' => $request->setTgl . ' ' . $request->waktuMulai,
                'closing_date' => $request->setTgl . ' ' . $request->waktuBerakhir
            ]);

            ActivityRepository::create([
                'user_id' => Auth::user()->id,
                'action' => Auth::user()->username . ' Set Waktu Presensi from ' . $request->setTgl . ' ' . $request->waktuMulai . ' to ' . $request->setTgl . ' ' . $request->waktuBerakhir,
            ]);

            session(['max_time_presensi' => $request->setTgl . ' ' . $request->waktuBerakhir]);

            return redirect()->route('admin.events.detailPresensi', ['code' => $code])->with('success_saved', 'Berhasil perpanjang waktu presensi!');

        } catch (\Throwable $e) {
            return redirect()->route('admin.events.detailPresensi', ['code' => $code])->with('error_saved', 'Gagal perpanjang waktu presensi: ' . $e->getMessage());
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
                        if ($request->has('present')) {
                            $checkUser->update([
                                'status_presensi' => 'Hadir',
                                'waktu_presensi' => now()
                            ]);

                            ActivityRepository::create([
                                'user_id' => Auth::user()->id,
                                'action' => Auth::user()->username . ' Presensikan User Hadir ' . $checkUser->name . ' Event Code ' . $code,
                            ]);

                            return redirect()->route('admin.events.detailPresensi', ['code' => $code]);

                        }

                        if ($request->has('block')) {
                            $checkUser->update([
                                'status_presensi' => 'Tidak Hadir',
                                'waktu_presensi' => now()
                            ]);

                            ActivityRepository::create([
                                'user_id' => Auth::user()->id,
                                'action' => Auth::user()->username . ' Presensikan User Tidak Hadir ' . $checkUser->name . ' Event Code ' . $code,
                            ]);

                            return redirect()->route('admin.events.detailPresensi', ['code' => $code]);

                        }

                        return redirect()->route('admin.events.detailPresensi', ['code' => $code])->with('error_saved', 'Gagal presensikan peserta, id peserta tidak terdaftar!');
                    }

                    return redirect()->route('admin.events.detailPresensi', ['code' => $code])->with('error_saved', 'Gagal presensikan peserta, id peserta tidak terdaftar!');

                } else {
                    return redirect()->route('admin.events.detailPresensi', ['code' => $code])->with('error_saved', 'Gagal presensikan peserta, waktu presensi telah berakhir!');
                }
            } else {
                return redirect()->route('admin.events.detailPresensi', ['code' => $code])->with('error_saved', 'Gagal presensikan peserta, tidak boleh akses melalui URL!');
            }

        } catch (\Throwable $e) {
            return redirect()->route('admin.events.detailPresensi', ['code' => $code])->with('error_saved', 'Gagal presensikan peserta: ' . $e->getMessage());
        }
    }

    public function onlineEvent()
    {
        return view('admin.Events.addOnlineEvent', $this->data);
    }

    public function saveOnlineEvent(Request $request)
    {
        try {

            if ($request->online_app == 'other' && $request->has('other_app') && !is_null($request->other_app)) {
                $request->online_app = $request->other_app;
            }

            $event_date = Carbon::parse($request->event_date)->format('Y-m-d H:i:s');

            if ($request->has('poster_event')) {
                $file = $request->file('poster_event');

                // Dapatkan ekstensi file
                $imageExtension = $file->getClientOriginalExtension();
                // Buat nama unik untuk file gambar
                $uniqueImageName = $file->getClientOriginalName() . '_' . time() . '.' . $imageExtension;

                $directory = public_path('events');

                // Membuat direktori jika tidak ada
                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }

                // Simpan data image ke dalam file di direktori yang diinginkan
                $request->file('poster_event')->move(public_path('events'), $uniqueImageName);
            }

            $newOnlineEv = OnlineEvents::create([
                'name' => $request->name,
                'event_date' => $request->event_date,
                'host' => $request->host,
                'speakers' => $request->speakers,
                'online_app' => $request->online_app,
                'link_pendaftaran' => $request->link_pendaftaran,
                'link_online' => $request->link_online,
                'user_access' => $request->user_access,
                'passcode' => $request->passcode,
                'poster' => ($request->has('poster_event') && !is_null($request->poster_event) ? url('events/' . $uniqueImageName) : null)
            ]);

            ActivityRepository::create([
                'user_id' => Auth::user()->id,
                'action' => Auth::user()->username . ' Create New Online Event Code ' . $newOnlineEv->code,
            ]);

            return redirect()->route('admin.events.index')->with('success_saved', 'Data online event berhasil ditambah!');

        } catch (\Throwable $e) {
            return redirect()->route('admin.events.index')->with('error_saved', 'Gagal menambahkan event online: ' . $e->getMessage());
        }

    }

    public function add()
    {
        return view('admin.Events.add', $this->data);
    }

    public function saveAdd(Request $request)
    {
        try {
            // Konversi tanggal dan waktu menjadi timestamp
            $event_date_datetime = Carbon::parse($request->event_date)->format('Y-m-d H:i:s');
            $type_event_arry = ['Sosialisasi', 'Kompetisi', 'Festival', 'Seminar', 'Training'];

            if (!in_array($request->type_event, $type_event_arry)) {
                return redirect()->back()->with('error_saved', 'Pilih type event diantara list yang tersedia');
            }

            if ($request->has('poster_event')) {
                $file = $request->file('poster_event');

                // Dapatkan ekstensi file
                $imageExtension = $file->getClientOriginalExtension();
                // Buat nama unik untuk file gambar
                $uniqueImageName = $file->getClientOriginalName() . '_' . time() . '.' . $imageExtension;

                $directory = public_path('events');

                // Membuat direktori jika tidak ada
                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }

                // Simpan data image ke dalam file di direktori yang diinginkan
                $request->file('poster_event')->move(public_path('events'), $uniqueImageName);

                // Simpan data ke dalam database
                $newEvent = Events::create([
                    'nama_event' => $request->event_name,
                    'location' => $request->location,
                    'type_event' => $request->type_event,
                    'organizer_name' => $request->organizer_name,
                    'event_section' => $request->event_section,
                    'event_date' => $event_date_datetime,
                    'poster' => url('events/' . $uniqueImageName)
                ]);

            } else {
                // Simpan data ke dalam database
                $newEvent = Events::create([
                    'nama_event' => $request->event_name,
                    'location' => $request->location,
                    'type_event' => $request->type_event,
                    'organizer_name' => $request->organizer_name,
                    'event_section' => $request->event_section,
                    'event_date' => $event_date_datetime,
                ]);
            }

            if ($request->has('organizer')) {
                foreach ($request->organizer as $organizer) {
                    EventManager::create([
                        'event_code' => $newEvent->code,
                        'name' => $organizer['nama'],
                        'email' => $organizer['email'],
                        'phone_number' => $organizer['phone_number'],
                        'section' => $organizer['section'],
                    ]);
                }
            }

            if ($request->has('participant')) {
                foreach ($request->participant as $participant) {
                    EventParticipant::create([
                        'event_code' => $newEvent->code,
                        'name' => $participant['nama'],
                        'email' => $participant['email'],
                        'phone_number' => $participant['phone_number'],
                    ]);
                }
            }

            ActivityRepository::create([
                'user_id' => Auth::user()->id,
                'action' => Auth::user()->username . ' Create New Event ID ' . $newEvent->id,
            ]);

            return redirect()->intended('/admin/events')->with('success_saved', 'Data event berhasil disimpan!');

        } catch (\Throwable $e) {
            // Tangani kesalahan jika terjadi
            return redirect()->intended('/admin/events')->with('error_saved', 'Terjadi kesalahan saat menyimpan data event: ' . $e->getMessage());
        }
    }

    public function update($code)
    {
        $event = Events::where('code', $code)->first();

        if (!$event) {
            return redirect()->route('admin.events.index');
        }

        $eventManager = EventManager::where('event_code', $code)->latest();
        $eventParticipant = EventParticipant::where('event_code', $code)->latest();
        $eventCode = $code;

        $itemsPerPage = 15;
        //print_r();
        // Menentukan jumlah halaman maksimum untuk semua data

        if ($itemsPerPage >= 15) {
            $totalPages = 15;
        }

        $eventManager = $eventManager->paginate($itemsPerPage);

        if ($eventManager->count() > 15) {
            $eventManager = $eventManager->paginate($itemsPerPage);

            if ($eventManager->currentPage() > $eventManager->lastPage()) {
                return redirect($eventManager->url($eventManager->lastPage()));
            }
        }

        $eventParticipant = $eventParticipant->paginate($itemsPerPage);

        if ($eventParticipant->count() > $itemsPerPage) {
            $eventParticipant = $eventParticipant->paginate($itemsPerPage);
            //dd($eventParticipant);
            if ($eventParticipant->currentPage() > $eventParticipant->lastPage()) {
                return redirect($eventParticipant->url($eventParticipant->lastPage()));
            }
        }

        session(['event_code' => $code]);

        return view('admin.Events.update', $this->data, compact('event', 'eventManager', 'eventParticipant', 'eventCode'));
    }

    public function searchUpdate($code, $role, Request $request)
    {
        try {
            $event = Events::where('code', $code)->first();
            $eventCode = $code;

            // Initialize the variables
            $eventManager = collect();
            $eventParticipant = collect();

            if (strtolower($role) == 'manager') {

                $searchDataManager = $request->input('search');
                $name = $searchDataManager['name'] ?? null;
                $email = $searchDataManager['email'] ?? null;
                $phone_number = $searchDataManager['phone_number'] ?? null;
                $section = $searchDataManager['section'] ?? null;

                $eventManagerQuery = EventManager::where('event_code', $code)->latest();

                if ($name) {
                    $eventManagerQuery->where('name', 'like', "$name%");
                }
                if ($email) {
                    $eventManagerQuery->where('email', 'like', "$email%");
                }
                if ($phone_number) {
                    $eventManagerQuery->where('phone_number', 'like', "$phone_number%");
                }
                if ($section) {
                    $eventManagerQuery->where('section', 'like', "$section%");
                }

                $itemsPerPage = 15;
                $eventManager = $eventManagerQuery->paginate($itemsPerPage);

                $fullUri = $request->getRequestUri();
                $eventManager->setPath($fullUri);

                $eventParticipant = EventParticipant::where('event_code', $code)->latest();

                $eventParticipant = $eventParticipant->paginate($itemsPerPage);

                if ($eventParticipant->count() > $itemsPerPage) {
                    $eventParticipant = $eventParticipant->paginate($itemsPerPage);
                    //dd($eventParticipant);
                    if ($eventParticipant->currentPage() > $eventParticipant->lastPage()) {
                        return redirect($eventParticipant->url($eventParticipant->lastPage()));
                    }
                }

                return view('admin.Events.update', compact('event', 'eventManager', 'eventParticipant', 'eventCode', 'searchDataManager'));
            }

            if (strtolower($role) == 'participant') {

                $searchDataParticipant = $request->input('search');
                $name = $searchDataParticipant['name'] ?? null;
                $email = $searchDataParticipant['email'] ?? null;
                $phone_number = $searchDataParticipant['phone_number'] ?? null;
                $section = $searchDataParticipant['section'] ?? null;

                $eventParticipantQuery = EventParticipant::where('event_code', $code)->latest();

                if ($name) {
                    $eventParticipantQuery->where('name', 'like', "$name%");
                }
                if ($email) {
                    $eventParticipantQuery->where('email', 'like', "$email%");
                }
                if ($phone_number) {
                    $eventParticipantQuery->where('phone_number', 'like', "$phone_number%");
                }

                $itemsPerPage = 15;
                $eventParticipant = $eventParticipantQuery->paginate($itemsPerPage);

                $fullUri = $request->getRequestUri();
                $eventParticipant->setPath($fullUri);

                $eventManager = EventManager::where('event_code', $code)->latest();

                $eventManager = $eventManager->paginate($itemsPerPage);

                if ($eventManager->count() > $itemsPerPage) {
                    $eventManager = $eventManager->paginate($itemsPerPage);
                    //dd($eventManager);
                    if ($eventManager->currentPage() > $eventManager->lastPage()) {
                        return redirect($eventManager->url($eventManager->lastPage()));
                    }
                }

                return view('admin.Events.update', compact('event', 'eventManager', 'eventParticipant', 'eventCode', 'searchDataParticipant'));

            }

            return redirect()->route('admin.events.update', ['code' => $code]);

        } catch (\Throwable $e) {
            return redirect()->route('admin.events.update', ['code' => $code]);
        }

    }

    public function updateParticipant($code, $id)
    {
        $eventCode = $code;
        $myEventParticipant = EventParticipant::where('event_code', $code)->where('id', decrypt($id))->first();

        return view('admin.Events.updateParticipant', $this->data, compact('eventCode', 'myEventParticipant'));
    }

    public function saveParticipant(Request $request, $code, $id)
    {
        try {
            EventParticipant::where('event_code', $code)->where('id', decrypt($id))->update([
                'name' => $request->name,
                'email' => $request->email ?? '(not set)',
                'phone_number' => $request->phone_number,
            ]);

            $requestData = $request->except('_token');
            $dataString = implode(', ', array_keys($requestData)) . ': ' . implode(', ', array_values($requestData));

            ActivityRepository::create([
                'user_id' => Auth::user()->id,
                'action' => Auth::user()->username . ' Update Participant ' . $dataString . ' [ID Event: ' . decrypt($id) . ']',
            ]);

            return redirect()->route('admin.events.update', ['code' => $code])->with('success_saved', 'Data berhasil disimpan!');

        } catch (\Throwable $e) {
            return redirect()->route('admin.events.update', ['code' => $code])->with('error_saved', 'Data gagal diupdate: ' . $e->getMessage());
        }
    }

    public function updateOnlineEvent($code)
    {
        $onlineEvents = OnlineEvents::where('code', $code)->first();
        $dataOnlineApp = ['Google Meet', 'Zoom', 'Microsoft Teams', 'Youtube'];

        return view('admin.Events.updateOnlineEvent', $this->data, compact('onlineEvents', 'dataOnlineApp'));
    }

    public function saveUpdateOnlineEvent(Request $request, $code)
    {
        try {

            if ($request->online_app == 'other' && $request->has('other_app') && !is_null($request->other_app)) {
                $request->online_app = $request->other_app;
            }

            $event_date = Carbon::parse($request->event_date)->format('Y-m-d H:i:s');

            if ($request->has('poster_event')) {
                $file = $request->file('poster_event');

                // Dapatkan ekstensi file
                $imageExtension = $file->getClientOriginalExtension();
                // Buat nama unik untuk file gambar
                $uniqueImageName = $file->getClientOriginalName() . '_' . time() . '.' . $imageExtension;

                $directory = public_path('events');

                // Membuat direktori jika tidak ada
                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }

                // Simpan data image ke dalam file di direktori yang diinginkan
                $request->file('poster_event')->move(public_path('events'), $uniqueImageName);
            }

            $check = OnlineEvents::where('code', $code)->first();

            $check->update([
                'name' => !is_null($request->name) ? $request->name : $check->name,
                'event_date' => !is_null($request->event_date) ? $request->event_date : $check->event_date,
                'host' => !is_null($request->host) ? $request->host : $check->host,
                'speakers' => !is_null($request->speakers) ? $request->speakers : $check->speakers,
                'online_app' => !is_null($request->online_app) ? $request->online_app : $check->online_app,
                'link_pendaftaran' => !is_null($request->link_pendaftaran) ? $request->link_pendaftaran : $check->link_pendaftaran,
                'link_online' => !is_null($request->link_online) ? $request->link_online : $check->link_online,
                'user_access' => !is_null($request->user_access) ? $request->user_access : $check->user_access,
                'passcode' => !is_null($request->passcode) ? $request->passcode : $check->passcode,
                'poster' => ($request->has('poster_event') && !is_null($request->poster_event) ? url('events/' . $uniqueImageName) : null)
            ]);

            return redirect()->route('admin.events.index')->with('success_updated', 'Data online event berhasil diupdate!');

        } catch (\Throwable $e) {
            return redirect()->route('admin.events.index')->with('error_updated', 'Gagal mengupdate event online: ' . $e->getMessage());
        }
    }
    public function updateManager($code, $id)
    {
        $eventCode = $code;
        $myEventManager = EventManager::where('event_code', $code)->where('id', decrypt($id))->first();

        return view('admin.Events.updateManager', $this->data, compact('eventCode', 'myEventManager'));
    }

    public function saveManager(Request $request, $code, $id)
    {
        try {
            EventManager::where('event_code', $code)->where('id', decrypt($id))->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
            ]);

            $requestData = $request->except('_token');
            $dataString = implode(', ', array_keys($requestData)) . ': ' . implode(', ', array_values($requestData));

            ActivityRepository::create([
                'user_id' => Auth::user()->id,
                'action' => Auth::user()->username . ' Update Pengurus ' . $dataString . 'ID Event: ' . decrypt($id),
            ]);

            return redirect()->route('admin.events.update', ['code' => $code])->with('success_saved', 'Data berhasil diupdate!');

        } catch (\Throwable $e) {
            return redirect()->route('admin.events.update', ['code' => $code])->with('error_saved', 'Data gagal diupdate: ' . $e->getMessage());
        }
    }

    public function delete($code)
    {
        try {
            Events::where('code', $code)->delete();
            EventManager::where('event_code', $code)->delete();
            EventParticipant::where('event_code', $code)->delete();

            ActivityRepository::create([
                'user_id' => Auth::user()->id,
                'action' => Auth::user()->username . ' Delete Event Code ' . $code,
            ]);

            return redirect()->intended('/admin/events')->with('delete_successfull', 'Data event berhasil dihapus!');

        } catch (\Throwable $e) {
            return redirect()->intended('/admin/events')->with('delete_successfull', 'Data event gagal dihapus: ' . $e->getMessage());
        }
    }

    public function deleteOnlineEvent($code)
    {
        try {
            OnlineEvents::where('code', $code)->delete();
            EventParticipant::where('event_code', $code)->delete();

            ActivityRepository::create([
                'user_id' => Auth::user()->id,
                'action' => Auth::user()->username . ' Delete Online Event Code ' . $code,
            ]);

            return redirect()->intended('/admin/events')->with('delete_successfull', 'Data event berhasil dihapus!');

        } catch (\Throwable $e) {
            return redirect()->intended('/admin/events')->with('delete_successfull', 'Data event gagal dihapus: ' . $e->getMessage());
        }
    }

    public function submitPengurus(Request $request, $code)
    {
        try {
            EventManager::create([
                'event_code' => $code,
                'name' => $request->nama,
                'email' => $request->email ?? '(not set)',
                'section' => $request->section,
                'phone_number' => $request->phone_number,
            ]);

            ActivityRepository::create([
                'user_id' => Auth::user()->id,
                'action' => Auth::user()->username . ' Create New Pengurus Event Code ' . $code,
            ]);

            return redirect()->back()->with('success_saved', 'Data berhasil ditambah!');

        } catch (\Throwable $e) {
            return redirect()->back()->with('error_saved', 'Data gagal ditambah: ' . $e->getMessage());
        }
    }

    public function submitPeserta(Request $request, $code)
    {
        try {
            EventParticipant::create([
                'event_code' => $code,
                'name' => $request->nama,
                'email' => $request->email ?? '(not set)',
                'phone_number' => $request->phone_number,
            ]);

            ActivityRepository::create([
                'user_id' => Auth::user()->id,
                'action' => Auth::user()->username . ' Create New Peserta Event Code ' . $code,
            ]);

            return redirect()->back()->with('success_saved', 'Data berhasil ditambah!');

        } catch (\Throwable $e) {
            return redirect()->back()->with('error_saved', 'Data gagal ditambah: ' . $e->getMessage());
        }
    }

    public function deleteManager($id)
    {
        try {
            $data = EventManager::where('id', decrypt($id))->first();

            $eventCode = session('eventCode');

            ActivityRepository::create([
                'user_id' => Auth::user()->id,
                'action' => Auth::user()->username . 'Delete Pengurus ' . $data->name . "Event Code $eventCode",
            ]);

            EventManager::where('id', decrypt($id))->forceDelete();

            return redirect()->back()->with('delete_successfull_manager', 'Data manager berhasil dihapus!');

        } catch (\Throwable $e) {
            return redirect()->back()->with('error_delete_manager', 'Data manager gagal dihapus: ' . $e->getMessage());
        }
    }

    public function deleteParticipant($id)
    {
        try {
            $data = EventParticipant::where('id', decrypt($id))->first();

            $eventCode = session('eventCode');

            ActivityRepository::create([
                'user_id' => Auth::user()->id,
                'action' => Auth::user()->username . 'Delete Peserta ' . $data->name . "Event Code $eventCode",
            ]);

            EventParticipant::where('id', decrypt($id))->forceDelete();

            return redirect()->back()->with('delete_successfull_participant', 'Data participant berhasil dihapus!');

        } catch (\Throwable $e) {
            return redirect()->back()->with('error_delete_participant', 'Data participant gagal dihapus: ' . $e->getMessage());
        }
    }

    public function createAttendance(Request $request)
    {
        try {
            $opening_date = Carbon::parse($request->opening_date)->format('Y-m-d H:i:s');
            $closing_date = Carbon::parse($request->closing_date)->format('Y-m-d H:i:s');

            //dd($opening_date, $closing_date, $request->all());
            Attendances::create([
                'event_code' => $request->event_code,
                'event_name' => $request->event_name,
                'status' => $request->status ?? null,
                'opening_date' => $opening_date,
                'closing_date' => $closing_date,
                'access_code' => $request->access_code,
            ]);

            $newUser = User::create([
                'username' => $request->access_code,
                'email' => $request->access_code,
                'password' => Hash::make($request->access_code),
                'role' => 'guest',
                'status' => 'active',
            ]);

            User::where('id', $newUser->id)->update([
                'role' => 'guest'
            ]);

            ActivityRepository::create([
                'user_id' => Auth::user()->id,
                'action' => Auth::user()->username . ' Create Presensi Event Code ' . $request->access_code,
            ]);

            return redirect()->intended('/admin/list_presensi')->with('success_saved', 'Data presensi berhasil dibuat!');

        } catch (\Throwable $e) {
            return redirect()->back()->with('error_saved', 'Data presensi gagal disimpan: ' . $e->getMessage());
        }
    }
}