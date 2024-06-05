<?php

namespace App\Http\Controllers;

use App\Models\EventParticipant;
use Illuminate\Http\Request;
use App\Repositories\ActivityRepository;
use App\Models\ChatDashboard;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Tutorials;
use App\Events\NotifyProcessed;
use App\Models\Attendances;
use Carbon\Carbon;

class DashboardController extends Controller
{
    private $data;

    public function __construct()
    {
        $this->data['currentActive'] = 'dashboard';
    }

    public function index(Request $request)
    {
        ActivityRepository::getActivityInfo();

        $tutorials = Tutorials::where('tutorial_category_id', 2)->with('categoryTutorial')->get();

        return view('dashboard', $this->data, compact('tutorials'));
    }

    public function submitChat(Request $request)
    {
        //dd(Session::get('csrf_token'));
        try {
            // data tidak masuk jika kurang dari 10 karakter
            $this->validate($request, [
                'name' => 'required|string|min:3',
                'email' => [
                    'required',
                    'email',
                    function ($attribute, $value, $fail) {
                        // Validasi email menggunakan ekspresi reguler
                        $emailRegex = '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';
                        if (!preg_match($emailRegex, $value)) {
                            return $fail('Please enter a valid email address.');
                        }
                    }
                ],
                'subject' => 'required',
                'message' => 'required|string|min:10',
            ]);

            $duplicateNewChat = ChatDashboard::where('email', $request->email)
                ->where('message', $request->message)->get();

            if ($duplicateNewChat->count() > 1) {
                return redirect()->back()->with('error_submit_chat', 'Chat sudah terkirim.. Tunggu balasan admin');

            } else {
                $existingChat = ChatDashboard::where('email', $request->email)->where('name', $request->name)
                    ->where('subject', $request->subject)->first();

                if (!$existingChat) {

                    $getChat = ChatDashboard::select('created_at')
                        ->groupBy('created_at')
                        ->havingRaw('COUNT(*) > 1')
                        ->count();

                    if ($getChat <= 5) {
                        $chat = new ChatDashboard();
                        $chat->name = $request->name;
                        $chat->email = $request->email;
                        $chat->subject = $request->subject;
                        $chat->message = $request->message;
                        $csrfToken = $chat->generateCsrfToken(); // Mengambil token CSRF yang sudah digenerate sebelumnya
                        $chat->csrf_token = $csrfToken;

                        // fungsi untuk membaca ketika kirim chat berbeda terlalu sering apabila lebih dari 10 kali
                        $error = ChatDashboard::checkCsrfTokenUsage($csrfToken);

                        if ($error) {
                            return redirect()->back()->with('error_submit_chat', $error);
                        } else {
                            // Penyimpanan chat hanya dilakukan jika tidak terjadi kesalahan pada token CSRF
                            $chat->save();

                            event(new NotifyProcessed(['count_message' => ChatDashboard::get()->count(), 'chats' => ChatDashboard::latest()->get()]));

                            ActivityRepository::create([
                                'action' => 'User Created a New Chat ID ' . $chat->id,
                            ]);

                        }
                    }

                }

                return redirect()->back()->with('success_submit_chat', 'Chat berhasil terkirim, kami akan membalas ke email yang tertera!');
            }

        } catch (\Throwable $e) {
            return redirect()->back()->with('error_submit_chat', 'Gagal simpan chat. ' . $e->getMessage());
        }
    }

    public function events()
    {
        if (session()->has('data_regis')) {
            return redirect()->route('events.viewPresensi');
        }

        if (session()->has('code')) {
            return redirect()->route('events.home');
        }

        return view('events', $this->data);
    }

    public function submitEvents(Request $request)
    {
        if (session()->has('data_regis')) {
            return redirect()->route('events.viewPresensi');
        }

        $request->validate([
            'code' => 'required|string',
        ]);

        $code = $request->input('code');
        $event = Attendances::where('access_code', $code);
        //dd($event);
        if ($event->exists()) {
            $request->session()->put('code', $code);
            return redirect()->route('events.home');

        } else {
            return redirect()->route('dashboard.events')->withErrors(['code' => 'Invalid code'])->withInput();
        }
    }

    public function homeEvent()
    {
        if (session()->has('data_regis')) {
            return redirect()->route('events.viewPresensi');
        }

        if (!session()->has('code')) {
            return redirect()->route('dashboard.events');
        }

        $code = session('code');
        $event = Attendances::select('events.*', 'attendances.*')->leftJoin('events', 'events.code', '=', 'attendances.event_code')->where('attendances.access_code', $code)->first();
        return view('home_event', compact('code', 'event'));
    }

    public function registerParticipant(Request $request)
    {
        if (session()->has('data_regis')) {
            return redirect()->route('events.viewPresensi');
        }

        if (!session()->has('code')) {
            return redirect()->route('dashboard.events');
        }

        if (Auth::check()) {
            return redirect()->route('events.home')->with('error', 'Anda telah login sebagai ' . Auth::user()->role . ' dengan username ' . Auth::user()->username . ' silakan logout terlebih dahulu');
        }

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone_number' => 'required|string|max:15',
            ]);

            session([
                'data_regis' => [
                    'event_code' => json_decode($request->event[0])->event_code,
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'phone_number' => $request->input('phone_number'),
                ]
            ]);

            $check = EventParticipant::where('event_code', session('data_regis.event_code'))->where('name', session('data_regis.name'))
                ->where('email', session('data_regis.email'))->first();

            if (!$check) {
                EventParticipant::create(session('data_regis'));
            }

            $existingUser = User::where('email', session('data_regis.email'))->where('username', session('data_regis.name'))->first();

            if ($existingUser) {
                throw ValidationException::withMessages(['email' => 'User or email already exists.']);
            }

            User::create([
                'username' => session('data_regis.name'),
                'password' => Hash::make(session('code') . '_' . session('data_regis.phone_number')),
                'email' => session('data_regis.email'),
                'status' => 'active',
                'role' => 'user'
            ]);

            return redirect()->route('events.viewPresensi')->with('success', 'Registrasi berhasil!');

        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal registrasi: ' . $e->getMessage());
        }

    }

    public function viewPresensi()
    {
        if (!session()->has('data_regis')) {
            return redirect()->route('events.home');
        }

        if (!session()->has('code')) {
            return redirect()->route('dashboard.events');
        }

        $event = Attendances::select('events.*', 'attendances.*', 'event_participant.*')
            ->leftJoin('events', 'events.code', '=', 'attendances.event_code')
            ->leftJoin('event_participant', 'event_participant.event_code', '=', 'events.code')
            ->where('attendances.access_code', session('code'))->first();

        $user = EventParticipant::where('name', session('data_regis.name'))->where('email', session('data_regis.email'))->first();

        return view('presensi', compact('event', 'user'));
    }

    public function submitPresensi(Request $request)
    {
        try {
            $waktuSubmit = $request->input('waktu_submit');

            // Konversi dari milidetik ke detik
            $timestampInSeconds = $waktuSubmit / 1000;

            // Buat instance Carbon dari timestamp
            $carbonDate = Carbon::createFromTimestamp($timestampInSeconds);

            EventParticipant::where('event_code', session('data_regis.event_code'))->where('name', session('data_regis.name'))->where('email', session('data_regis.email'))->update([
                'status_presensi' => 'Hadir',
                'waktu_presensi' => $carbonDate->toDateTimeString()
            ]);

            return redirect()->back()->with('success', 'Berhasil melakukan presensi !');

        } catch (\Throwable $e) {
            return redirect()->back()->with('error_submit', 'Gagal melakukan presensi: ' . $e->getMessage());
        }

    }
}