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
use Carbon\Carbon;

class EventsAdminController extends Controller
{
    private $data;

    public function __construct()
    {
        $this->data['currentAdminMenu'] = 'events';
        $this->data['currentTitle'] = 'Events | Artec Coding Indonesia';
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

        $eventNotSetPresensi = Events::whereNotIn('code', Attendances::select('event_code')->get()->pluck('event_code'))->get();

        return view('admin.Events.index', $this->data, compact('events', 'eventNotSetPresensi'));
    }

    public function listPresensi()
    {
        $listPresensi = Attendances::select(
            'attendances.*',
            DB::raw('(SELECT COUNT(*) FROM event_participant WHERE event_participant.event_code = attendances.event_code) as total_peserta'),
            DB::raw('(SELECT COUNT(*) FROM event_participant WHERE event_participant.event_code = attendances.event_code and event_participant.status_presensi = "Hadir") as peserta_hadir'),
            DB::raw('(SELECT COUNT(*) FROM event_participant WHERE event_participant.event_code = attendances.event_code and event_participant.status_presensi = "Tidak Hadir") as peserta_tidak_hadir'),
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
            )->get();

        $eventNotSetPresensi = Events::whereNotIn('code', Attendances::select('event_code')->get()->pluck('event_code'))->get();

        return view('admin.Events.listPresensi', $this->data, compact('listPresensi', 'eventNotSetPresensi'));
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