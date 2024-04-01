<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Support\Facades\Session;
use GeoIp2\Database\Reader;

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
                $databasePath = public_path('GeoLite2-City.mmdb');
                $reader = new Reader($databasePath);

                try {
                    //dd($getToken);
                    $ch = curl_init('https://api.ipify.org');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $publicIpAddress = curl_exec($ch);
                    curl_close($ch);
                    $userAgent = $request->header('User-Agent');

                    // Mendapatkan informasi lokasi dari IP publik
                    $record = $reader->city($publicIpAddress);

                    // Dapatkan informasi yang Anda butuhkan, seperti nama kota, negara, koordinat, dsb.
                    $cityName = $record->city->name;
                    $countryName = $record->country->name;
                    $latitude = $record->location->latitude;
                    $longitude = $record->location->longitude;

                    //dd($cityName, $latitude, $longitude, $userAgent);

                    session([
                        'myActivity' => [
                            'ip_address' => $publicIpAddress,
                            'user_agent' => $userAgent,
                            'latitude' => $latitude,
                            'longitude' => $longitude,
                            'country' => $countryName,
                            'city' => $cityName,
                        ]
                    ]);

                } catch (\Throwable $e) {
                    //dd($e->getMessage());
                    return view('login', $this->data);
                }

            }

            return view('login', $this->data);

        }
    }

    public function login(Request $request)
    {
        // Validasi data input
        $credentials = $request->validate([
            'username_or_email' => 'required|string',
            'password' => 'required|string',
        ]);

        // Lakukan proses login
        if (
            Auth::attempt(['email' => $credentials['username_or_email'], 'password' => $credentials['password']]) ||
            Auth::attempt(['username' => $credentials['username_or_email'], 'password' => $credentials['password']])
        ) {
            // Jika autentikasi berhasil, arahkan pengguna sesuai peran
            if (Auth::user()->role == 'admin') {

                User::where('id', Auth::user()->id)->update([
                    'last_login' => now(),
                ]);

                //dd(session('myActivity'), Session::get('csrf_token'));
                Activity::create(array_merge(session('myActivity'), [
                    'user_id' => Auth::user()->id,
                    'action' => Auth::user()->username . ' Access Login Role ' . Auth::user()->role
                ]));

                return redirect()->route('admin.dashboard');

            } elseif (Auth::user()->role == 'pengurus') {

                User::where('id', Auth::user()->id)->update([
                    'last_login' => now(),
                ]);

                Activity::create(array_merge(session('myActivity'), [
                    'user_id' => Auth::user()->id,
                    'action' => Auth::user()->username . ' Access Login Role ' . Auth::user()->role
                ]));

                return redirect()->route('pengurus.dashboard');

            } elseif (Auth::user()->role == 'user') {

                User::where('id', Auth::user()->id)->update([
                    'last_login' => now(),
                ]);

                Activity::create(array_merge(session('myActivity'), [
                    'user_id' => Auth::user()->id,
                    'action' => Auth::user()->username . ' Access Login Role ' . Auth::user()->role
                ]));

                return redirect()->route('user.dashboard');

            } else {
                return redirect()->route('form.login')->withErrors(['message' => 'Username atau password tidak terdaftar']);
            }
        }

        // Jika autentikasi gagal, kembalikan pengguna ke halaman login dengan pesan error
        return redirect()->route('form.login')->withErrors(['message' => 'Username atau password salah']);
    }

    public function logout()
    {
        Auth::logout();

        session()->invalidate();

        session()->regenerateToken();

        return redirect('/');
    }
}