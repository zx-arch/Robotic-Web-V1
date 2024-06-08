<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventParticipant;
use App\Models\User;
use App\Models\Settings;
use App\Repositories\ActivityRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSettingsController extends Controller
{
    public function index()
    {
        $myData = EventParticipant::select('event_participant.*', 'settings.foto_profil')->leftJoin('settings', 'settings.email_pengelola', '=', 'event_participant.email')->where('event_participant.email', Auth::user()->email)->first();

        return view('user.settings', compact('myData'));
    }
    public function save(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {

                EventParticipant::where('email', Auth::user()->email)->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                ]);

                $user = User::where('email', Auth::user()->email)->first();

                if ($request->has('new_password') && $request->has('confirm_password') && !is_null($request->new_password) && !is_null($request->confirm_password)) {

                    if ($request->new_password == $request->confirm_password) {

                        if (Hash::needsRehash($request->new_password)) {
                            $hashedPassword = Hash::make($request->new_password);
                        } else {
                            $hashedPassword = $request->new_password;
                        }

                        $user->update([
                            'password' => $hashedPassword,
                        ]);
                    }
                }

                if ($request->hasFile('foto_profil')) { // Periksa apakah file gambar dikirimkan

                    $directory = public_path('events/user/foto_profil/');
                    $imageExtension = $request->file('foto_profil')->getClientOriginalExtension();
                    $uniqueImageName = time() . '_' . $request->file('foto_profil')->getClientOriginalName();

                    // Membuat direktori jika tidak ada
                    if (!file_exists($directory)) {
                        mkdir($directory, 0777, true);
                    }

                    // Simpan data image ke dalam file di direktori yang diinginkan
                    $request->file('foto_profil')->move(public_path('events/user/foto_profil/'), $uniqueImageName);
                    $checkSettings = Settings::where('email_pengelola', Auth::user()->email)->first();

                    if (!$checkSettings) {
                        Settings::create([
                            'user_id' => $user->id,
                            'nama_pengelola' => $request->name,
                            'email_pengelola' => $request->email,
                            'foto_profil' => $uniqueImageName, // Simpan path gambar ke database
                        ]);
                    } else {
                        Settings::where('email_pengelola', $checkSettings->email_pengelola)->update([
                            'foto_profil' => $uniqueImageName
                        ]);
                    }
                }
            });

            ActivityRepository::create([
                'user_id' => Auth::user()->id,
                'action' => Auth::user()->username . ' Update Setting Account',
            ]);

            return redirect()->intended('user/settings')->with('success_saved', 'Data berhasil diupdate!');

        } catch (\Throwable $e) {
            return redirect()->intended('user/settings')->with('error_saved', 'Data gagal diupdate: ' . $e->getMessage());
        }

    }
}