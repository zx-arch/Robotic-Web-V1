<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Settings;
use App\Models\Activity;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminSettingsController extends Controller
{
    private $data;
    public function __construct()
    {
        $this->data['currentAdminMenu'] = 'settings';
        $this->data['currentTitle'] = 'Settings | Artec Coding Indonesia';
    }
    public function index()
    {
        $settings = Settings::where('user_id', Auth::user()->id)->first();
        return view('admin.Settings.index', $this->data, compact('settings'));
    }
    public function save(Request $request)
    {
        try {

            $check = Settings::where('user_id', Auth::user()->id)->first();

            if ($request->hasFile('image')) { // Periksa apakah file gambar dikirimkan

                $directory = public_path('assets/foto_profil/');
                $imageExtension = $request->file('image')->getClientOriginalExtension();
                $uniqueImageName = time() . '_' . $request->file('image')->getClientOriginalName();

                // Membuat direktori jika tidak ada
                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }

                // Simpan data image ke dalam file di direktori yang diinginkan
                $request->file('image')->move(public_path('assets/foto_profil/'), $uniqueImageName);

            } else {
                $uniqueImageName = null; // Jika tidak ada file gambar yang dikirimkan, set imagePath menjadi null
            }

            DB::transaction(function () use ($check, $request, $uniqueImageName) {

                if (!$check) {
                    $check = Settings::create([
                        'user_id' => Auth::user()->id,
                        'nama_pengelola' => $request->nama_pengelola,
                        'email_pengelola' => $request->email_pengelola,
                        'instansi' => $request->instansi,
                        'jabatan' => $request->jabatan,
                        'foto_profil' => $uniqueImageName, // Simpan path gambar ke database
                    ]);

                } else {
                    $check->update([
                        'nama_pengelola' => (($request->has('nama_pengelola')) ? $request->nama_pengelola : $check->nama_pengelola),
                        'email_pengelola' => (($request->has('email_pengelola')) ? $request->email_pengelola : $check->email_pengelola),
                        'instansi' => (($request->has('instansi')) ? $request->instansi : $check->instansi),
                        'jabatan' => (($request->has('jabatan')) ? $request->jabatan : $check->jabatan),
                        'foto_profil' => ((isset ($uniqueImageName)) ? $uniqueImageName : $check->foto_profil), // Simpan path gambar ke database
                    ]);
                }

                $user = User::find(Auth::user()->id);

                if ($request->has('password')) {
                    if (Hash::needsRehash($request->password)) {
                        $hashedPassword = Hash::make($request->password);
                    } else {
                        // Jika password sudah menggunakan algoritma yang sesuai, gunakan yang sudah ada
                        $hashedPassword = $request->password;
                    }

                    $user->update([
                        'password' => $hashedPassword,
                    ]);
                }

                $requestData = $request->except('_token');
                $dataString = implode(', ', array_keys($requestData)) . ': ' . implode(', ', array_values($requestData));

                Activity::create(array_merge(session('myActivity'), [
                    'user_id' => Auth::user()->id,
                    'action' => Auth::user()->username . ' Update Setting Account ' . $dataString . ' ID ' . $check->id,
                ]));
            });

            return redirect()->back()->with('success_saved', 'Data berhasil disimpan!');

        } catch (\Throwable $e) {
            return redirect()->back()->with('error_saved', 'Data gagal disimpan. ' . $e->getMessage());
        }
    }

}