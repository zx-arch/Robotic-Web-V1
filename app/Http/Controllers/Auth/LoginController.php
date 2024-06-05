<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\ActivityRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tutorials;
use GeoIp2\Database\Reader;
use App\Models\User;
use App\Models\Activity;
use App\Models\ChatDashboard;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    private $data;

    public function __construct()
    {
        $this->data['currentActive'] = 'login';

        $data = DB::table('information_schema.columns')
            ->select('column_name')
            ->where('table_name', 'users')
            ->get();

        $columns = $data->pluck('column_name')->toArray();

        if (!in_array('count_failed_login', $columns)) {
            DB::statement('ALTER TABLE users ADD COLUMN count_failed_login BIGINT UNSIGNED');
        }

        if (!in_array('time_start_failed_login', $columns)) {
            DB::statement('ALTER TABLE users ADD COLUMN time_start_failed_login TIMESTAMP NULL DEFAULT NULL');
        }

        if (!in_array('time_end_failed_login', $columns)) {
            DB::statement('ALTER TABLE users ADD COLUMN time_end_failed_login TIMESTAMP NULL DEFAULT NULL');
        }
    }

    public function index(Request $request)
    {
        if (!session()->has('myActivity')) {
            ActivityRepository::getActivityInfo();
        }

        if (session()->has('failed_login')) {
            // Ambil waktu sekarang
            $dateTime1 = Carbon::now();

            // Query time_end_failed_login
            $user = User::where('username', session('failed_login.username'))
                ->orWhere('email', session('failed_login.username'))
                ->first();

            // Periksa apakah data user ditemukan dan time_end_failed_login tidak kosong
            if ($user && $user->time_end_failed_login) {
                // Konversi time_end_failed_login menjadi objek Carbon
                $dateTime2 = Carbon::parse($user->time_end_failed_login);

                // Bandingkan waktu sekarang dengan time_end_failed_login
                if ($dateTime1->greaterThan($dateTime2)) {
                    // Hapus session
                    session()->forget('failed_login');

                } else {
                    // Hitung sisa waktu (detik) dan sisa percobaan
                    $delay = session('delay');
                    $remainingCounts = 5 - session('counts') % 5;
                    $remainingTime = $dateTime2->diffInSeconds($dateTime1);

                    session()->put('failed_login.delay', $remainingTime);
                }
            }
        }

        if (Auth::check()) {

            if (!session('blocked_ip')) {
                return redirect()->intended('/' . Auth::user()->role);
            }

        } else {

            if (session()->has('data_regis')) {
                session()->forget('data_regis');
            }

            $tutorials = Tutorials::where('tutorial_category_id', 2)->with('categoryTutorial')->get();
            //dd($tutorials);
            return view('login', $this->data, compact('tutorials'));
        }

    }

    public function login(Request $request)
    {
        $credentials = $request->only('username_or_email', 'password');

        if (
            Auth::attempt(['email' => $credentials['username_or_email'], 'password' => $credentials['password']]) ||
            Auth::attempt(['username' => $credentials['username_or_email'], 'password' => $credentials['password']])
        ) {
            // Authentication passed...
            $user = Auth::user();
            session(['username' => Auth::user()->username]);

            if ($user->role == 'admin') {

                User::where('id', Auth::user()->id)->update([
                    'count_failed_login' => null,
                    'last_login' => now(),
                    'time_start_failed_login' => null,
                    'time_end_failed_login' => null
                ]);

                session()->forget('failed_login');

                session(['countChat' => ChatDashboard::get()->count()]);

                // dd(session('myActivity'), Session::get('csrf_token'));
                if (session()->has('myActivity')) {

                    ActivityRepository::create([
                        'user_id' => Auth::user()->id,
                        'action' => Auth::user()->username . ' Access Login Role ' . Auth::user()->role
                    ]);

                }

                Cookie::queue('user_email', $credentials['username_or_email'], 1440);

                return redirect()->intended('/admin');

            } elseif ($user->role == 'pengurus') {

                User::where('id', Auth::user()->id)->update([
                    'count_failed_login' => null,
                    'last_login' => now(),
                    'time_start_failed_login' => null,
                    'time_end_failed_login' => null
                ]);

                session()->forget('failed_login');

                // dd(session('myActivity'), Session::get('csrf_token'));
                if (session()->has('myActivity')) {

                    ActivityRepository::create([
                        'user_id' => Auth::user()->id,
                        'action' => Auth::user()->username . ' Access Login Role ' . Auth::user()->role
                    ]);

                }

                Cookie::queue('user_email', $credentials['username_or_email'], 1440);

                return redirect()->intended('/pengurus');

            } elseif ($user->role == 'user') {

                User::where('id', Auth::user()->id)->update([
                    'count_failed_login' => null,
                    'last_login' => now(),
                    'time_start_failed_login' => null,
                    'time_end_failed_login' => null
                ]);

                session()->forget('failed_login');

                // dd(session('myActivity'), Session::get('csrf_token'));
                if (session()->has('myActivity')) {
                    ActivityRepository::create([
                        'user_id' => Auth::user()->id,
                        'action' => Auth::user()->username . ' Access Login Role ' . Auth::user()->role
                    ]);
                }

                Cookie::queue('user_email', $credentials['username_or_email'], 1440);

                return redirect()->intended('/user');

            } else {
                return redirect()->intended('/');
            }
        }

        // program terjadi delay ketika 5x salah password
        $check = User::where('username', $credentials['username_or_email'])->first();
        //dd($check, $credentials['username_or_email']);

        if ($check) {
            $check->count_failed_login += 1;
            $check->update(['count_failed_login' => $check->count_failed_login]);

            $counts = $check->count_failed_login;
            $delay = (intdiv($counts, 5) * 20); // Menghitung delay berdasarkan kelipatan 5

            if ($counts >= 5) {
                // Menghitung waktu saat ini
                $now = Carbon::now();

                // Menambah detik berdasarkan delay
                $futureTime = $now->copy()->addSeconds($delay);

                DB::table('users')->where('username', $credentials['username_or_email'])->update([
                    'time_start_failed_login' => $now,
                    'time_end_failed_login' => $futureTime
                ]);

                session([
                    'failed_login' => [
                        'username' => $credentials['username_or_email'],
                        'counts' => $counts,
                        'delay' => $delay,
                    ]
                ]);

            } else {
                session()->forget('failed_login');
            }

            return redirect()->back()->withErrors([
                'message' => 'These credentials do not match our records. ' . $check->count_failed_login . ' password wrong!',
                'counts' => $counts,
                'delay' => $delay,
            ]);

        } else {
            return redirect()->back()->withErrors(['message' => 'These credentials do not match our records!']);
        }

    }

    public function logout()
    {
        Auth::logout();

        session()->flush();

        Cookie::queue(Cookie::forget('user_email'));

        return redirect('/');
    }
}