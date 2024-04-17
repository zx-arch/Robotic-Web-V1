<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tutorials;
use GeoIp2\Database\Reader;
use App\Models\User;
use App\Models\Activity;
use App\Models\ChatDashboard;

class LoginController extends Controller
{
    private $data;
    public function __construct()
    {
        $this->data['currentActive'] = 'login';
    }
    public function index(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('home_dashboard');
        } else {

            if (!session()->has('myActivity')) {

                try {
                    $databasePath = public_path('GeoLite2-City.mmdb');
                    $reader = new Reader($databasePath);

                    $userAgent = $request->header('User-Agent');

                    if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
                        $_SERVER['REMOTE_ADDR'] = '103.169.39.38';
                    }

                    // Mendapatkan informasi lokasi dari IP publik
                    $record = $reader->city($_SERVER['REMOTE_ADDR']);

                    // Dapatkan informasi yang Anda butuhkan, seperti nama kota, negara, koordinat, dsb.
                    $cityName = $record->city->name;
                    $countryName = $record->country->name;
                    $latitude = $record->location->latitude;
                    $longitude = $record->location->longitude;
                    $subdivisions = $record->subdivisions[0]->names['de'];

                    //dd($cityName, $latitude, $longitude, $userAgent);

                    // Tetapkan nilai endpoint ke dalam session hanya jika referer tidak kosong
                    session([
                        'myActivity' => [
                            'ip_address' => $_SERVER['REMOTE_ADDR'],
                            'user_agent' => $userAgent,
                            'latitude' => $latitude,
                            'longitude' => $longitude,
                            'country' => $countryName,
                            'city' => $cityName . (isset($subdivisions) ? ', ' . $subdivisions : ''),
                            'metadata' => json_encode($request->header()),
                        ]
                    ]);

                } catch (\Throwable $e) {
                    //dd($e->getMessage());
                    $tutorials = Tutorials::where('tutorial_category_id', 2)->with('categoryTutorial')->get();

                    return view('login', $this->data, compact('tutorials'));
                }

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
                    'last_login' => now(),
                ]);

                session(['countChat' => ChatDashboard::get()->count()]);

                // dd(session('myActivity'), Session::get('csrf_token'));
                if (session()->has('myActivity')) {
                    Activity::create(array_merge(session('myActivity'), [
                        'user_id' => Auth::user()->id,
                        'action' => Auth::user()->username . ' Access Login Role ' . Auth::user()->role
                    ]));
                }

                return redirect()->route('admin.dashboard');

            } elseif ($user->role == 'pengurus') {

                User::where('id', Auth::user()->id)->update([
                    'last_login' => now(),
                ]);

                // dd(session('myActivity'), Session::get('csrf_token'));
                if (session()->has('myActivity')) {
                    Activity::create(array_merge(session('myActivity'), [
                        'user_id' => Auth::user()->id,
                        'action' => Auth::user()->username . ' Access Login Role ' . Auth::user()->role
                    ]));
                }

                return redirect()->route('pengurus.dashboard');

            } elseif ($user->role == 'user') {

                User::where('id', Auth::user()->id)->update([
                    'last_login' => now(),
                ]);

                // dd(session('myActivity'), Session::get('csrf_token'));
                if (session()->has('myActivity')) {
                    Activity::create(array_merge(session('myActivity'), [
                        'user_id' => Auth::user()->id,
                        'action' => Auth::user()->username . ' Access Login Role ' . Auth::user()->role
                    ]));
                }

                return redirect()->route('user.dashboard');

            } else {
                return redirect()->intended('/');
            }
        }

        return redirect()->back()->withErrors(['username_or_email' => 'These credentials do not match our records.']);
    }
    public function logout()
    {
        Auth::logout();

        session_unset();

        session()->invalidate();

        session()->regenerateToken();

        return redirect('/');
    }
}