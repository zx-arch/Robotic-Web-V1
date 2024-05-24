<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            DB::raw('COUNT(event_manager.event_code) as total_pengurus'),
            DB::raw('COUNT(event_participant.event_code) as total_peserta'),
        )
            ->leftJoin('event_manager', 'events.code', '=', 'event_manager.event_code')
            ->leftJoin('event_participant', 'events.code', '=', 'event_participant.event_code')
            ->groupBy(
                'events.code',
                'events.nama_event',
                'events.location',
                'events.event_date',
                'events.organizer_name',
                'events.event_section',
                'events.poster',
                'events.created_at',
            )
            ->latest()
            ->get();

        return view('admin.Events.index', $this->data, compact('events'));
    }

    public function add()
    {
        return view('admin.Events.add', $this->data);
    }

    public function saveAdd(Request $request)
    {
        try {
            //dd($request->all());

            // Konversi tanggal dan waktu menjadi timestamp
            $event_date_datetime = Carbon::parse($request->event_date)->format('Y-m-d H:i:s');

            // Simpan data ke dalam database
            $new = Events::create([
                'nama_event' => $request->event_name,
                'location' => $request->location,
                'organizer_name' => $request->organizer_name,
                'event_section' => $request->event_section,
                'event_date' => $event_date_datetime,
            ]);

            if ($request->has('poster_event')) {

            }

            if ($request->has('organizer')) {
                foreach ($request->organizer as $organizer) {
                    EventManager::create([
                        'event_code' => $new->code,
                        'name' => $organizer['nama'],
                        'email' => $organizer['email'],
                        'phone_number' => $organizer['phone_number'],
                        'section' => $organizer['section'],
                    ]);
                }
            }

            if ($request->has('participant')) {
                foreach ($request->participant as $participant) {
                    EventManager::create([
                        'event_code' => $new->code,
                        'name' => $participant['nama'],
                        'email' => $participant['email'],
                        'phone_number' => $participant['phone_number'],
                        'section' => $participant['section'],
                    ]);
                }
            }

            return redirect()->intended('/admin/events')->with('success_saved', 'Data event berhasil disimpan!');

        } catch (\Throwable $e) {
            // Tangani kesalahan jika terjadi
            return redirect()->intended('/admin/events')->with('error_saved', 'Terjadi kesalahan saat menyimpan data event: ' . $e->getMessage());
        }
    }

    public function update($code)
    {
        $events = Events::where('code', $code)->first();
        $eventManager = EventManager::where('event_code', $code)->get();
        $eventParticipant = EventParticipant::where('event_code', $code)->get();

        return view('admin.Events.update', $this->data, compact('events', 'eventManager', 'eventParticipant'));
    }
}