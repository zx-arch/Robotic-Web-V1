<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Activity;
use App\Models\Settings;
use Illuminate\Validation\ValidationException;
use Illuminate\Pagination\LengthAwarePaginator;


class DaftarPengguna extends Controller
{
    private $data;

    public function __construct()
    {
        $this->data['currentAdminMenu'] = 'authentication';
        $this->data['currentAdminSubMenu'] = 'account';
        $this->data['currentTitle'] = 'Account | Artec Coding Indonesia';
    }

    public function index()
    {
        $users = Users::withTrashed()->latest();
        session(['data_users' => $users->get()]);

        if (session()->has('sorting')) {
            session()->forget('sorting');
        }

        // Menentukan jumlah item per halaman
        $itemsPerPage = 15;
        //print_r();
        // Menentukan jumlah halaman maksimum untuk semua data
        $users = $users->paginate($itemsPerPage);

        if ($itemsPerPage >= 15) {
            $totalPages = 15;
        }

        $fullUri = route('daftar_pengguna.index', request()->query() + ['sort' => 'id']);
        //dd($fullUri);
        if ($users->count() > 15) {
            $users = $users->paginate($itemsPerPage);
            //dd($users);
            if ($users->currentPage() > $users->lastPage()) {
                return redirect($users->url($users->lastPage()));
            }
        }

        $allUser = Users::count();
        $userActive = Users::where('status', 'active')->count();
        $userInActive = Users::where('status', 'inactive')->count();
        $userDeleted = Users::where('status', 'deleted')->count();

        return view('admin.DaftarPengguna.index', $this->data, compact('users', 'allUser', 'userInActive', 'userActive', 'userDeleted', 'itemsPerPage', 'fullUri'));
    }

    public function search(Request $request, $sort = '')
    {
        // Mendapatkan data pencarian dari request
        $searchData = $request->input('search');
        // Lakukan sesuatu dengan data pencarian, contoh: mencari data di database
        $username = $searchData['username'] ?? null;
        $email = $searchData['email'] ?? null;
        $status = $searchData['status'] ?? null;
        $created_at = $searchData['created_at'] ?? null;
        $last_login = $searchData['last_login'] ?? null;

        // Misalnya, Anda ingin mencari data user berdasarkan username, email, status, created_at, atau last_login
        $users = Users::query()->withTrashed()->latest();

        if ($sort != '') {
            $users = Users::query()->withTrashed()->orderBy($sort)->latest();
        }

        if ($status !== null || $last_login !== null && ($username !== null || $email !== null || $created_at !== null)) {
            // Menggunakan where untuk menambahkan kondisi pencarian tambahan
            $users->where('status', $status);

            if ($username !== null) {
                $users->where('username', 'like', "$username%");
            }

            if ($email !== null) {
                $users->where('email', 'like', "$email%");
            }

            if ($created_at !== null) {
                $users->where('created_at', 'like', "$created_at%");
            }

            if ($last_login !== null) {
                $users->where('last_login', 'like', "$last_login%");
            }

        } elseif ($username !== null || $status !== null || $email !== null || $created_at !== null || $last_login !== null) {
            $users->where(function ($query) use ($username, $status, $email, $created_at, $last_login) {
                if ($username !== null) {
                    $query->where('username', 'like', "$username%");
                }

                if ($status !== null) {
                    $query->orWhere('status', $status);
                }

                if ($email !== null) {
                    $query->orWhere('email', 'like', "$email%");
                }

                if ($created_at !== null) {
                    $query->orWhere('created_at', 'like', "$created_at%");
                }

                if ($last_login !== null) {
                    $query->orWhere('last_login', 'like', "$last_login%");
                }
            });
        }

        session(['data_users' => $users->get()]);
        session(['request_query' => $request->query()]);

        $totalUsers = $users->count();

        // Menentukan jumlah item per halaman
        $itemsPerPage = 15;

        // Menentukan jumlah halaman maksimum untuk semua data
        $totalPagesAll = ceil($totalUsers / $itemsPerPage);
        $users = $users->paginate($itemsPerPage);

        // Mendapatkan URI lengkap dari request
        $fullUri = route('daftar_pengguna.index', request()->query());
        //dd($fullUri);

        if ($totalPagesAll >= 15) {
            $totalPages = 15;
        }

        $users->setPath($fullUri);

        if ($users->count() > 15) {
            $users = $users->paginate($itemsPerPage);
            //dd($users);
            if ($users->currentPage() > $users->lastPage()) {
                return redirect($users->url($users->lastPage()));
            }
        }

        $allUser = Users::count();
        $userActive = Users::where('status', 'active')->count();
        $userInActive = Users::where('status', 'inactive')->count();
        $userDeleted = Users::where('status', 'deleted')->count();

        return view('admin.DaftarPengguna.index', $this->data, compact('users', 'allUser', 'userInActive', 'userActive', 'userDeleted', 'searchData', 'itemsPerPage', 'fullUri'));

    }

    public function sort(Request $request, $sort)
    {
        $users = collect(session('data_users'));

        if ($sort == 'status') {
            $sort = 'status_id';
        }

        session(['sorting' => $sort]);

        // Mengurutkan data berdasarkan username
        $users = $users->sortBy($sort);

        // Menentukan jumlah item per halaman
        $itemsPerPage = 15;

        // Mengambil parameter page dari URL
        $page = $request->input('page', 1);

        // Mengambil data yang akan ditampilkan pada halaman tersebut
        $users = new LengthAwarePaginator(
            $users->forPage($page, $itemsPerPage),
            $users->count(),
            $itemsPerPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $allUser = Users::count();
        $userActive = Users::where('status', 'active')->count();
        $userInActive = Users::where('status', 'inactive')->count();
        $userDeleted = Users::where('status', 'deleted')->count();

        return view('admin.DaftarPengguna.index', $this->data, compact('users', 'allUser', 'userInActive', 'userActive', 'userDeleted'));
    }


    public function add()
    {
        return view('admin.DaftarPengguna.add', $this->data);
    }

    public function delete($user_id)
    {
        //dd($user_id);
        try {
            $user = User::find(decrypt($user_id));

            $user->update([
                'status' => 'deleted'
            ]);

            $user->delete();

            Activity::create(array_merge(session('myActivity'), [
                'user_id' => Auth::user()->id,
                'action' => Auth::user()->username . ' Deleted Account User ' . $user->username . ' ID ' . decrypt($user_id),
            ]));

            return redirect()->route('daftar_pengguna.index')->with('success_deleted', 'Data berhasil dihapus!');

        } catch (\Throwable $e) {
            return redirect()->back()->with('error_deleted', 'Data gagal dihapus. ' . $e->getMessage());
        }

    }
    public function update($user_id)
    {
        try {
            $user = Users::where('id', decrypt($user_id))->first();
            return view('admin.DaftarPengguna.update', $this->data, compact('user'));

        } catch (\Throwable $e) {
            return redirect()->route('daftar_pengguna.index')->with('error_view', 'Halaman tidak tersedia, pastikan user terdaftar dan belum dihapus!');
        }
    }

    public function save(Request $request)
    {
        // dd($request->all());
        try {
            $this->validate($request, [
                'username' => 'required|string|min:3',
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
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    function ($attribute, $value, $fail) {
                        // Validasi password menggunakan ekspresi reguler
                        $passwordRegex = '/^(?=.*[a-zA-Z0-9!@#$%^&*()_+\[\]{};:\'\"|,.<>\/?]).{8,}$/';
                        if (!preg_match($passwordRegex, $value)) {
                            return $fail('Password must be at least 8 characters.');
                        }
                    }
                ],
                'status' => 'required|string',
                'role' => 'required|string',
            ]);

            $existingUser = User::where('email', $request->email)->orWhere('username', $request->username)->first();

            if ($existingUser) {
                throw ValidationException::withMessages(['email' => 'User or email already exists.']);
            }
            //dd($request->all());
            $newUser = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'email' => $request->email,
                'status' => $request->status,
                'role' => $request->role
            ]);

            Activity::create(array_merge(session('myActivity'), [
                'user_id' => Auth::user()->id,
                'action' => Auth::user()->username . ' Add New Account User ' . $newUser->username . ' ID ' . $newUser,
            ]));

            return redirect()->route('daftar_pengguna.index')->with('success_submit_save', 'User Berhasil Dibuat!');

        } catch (ValidationException $e) {
            // Tangkap kesalahan validasi
            return redirect()->back()->withErrors($e->errors())->withInput();

        } catch (\Throwable $e) {
            return redirect()->back()->with('error_submit_save', $e->getMessage());
        }

    }

    public function view($user_id)
    {
        try {
            $user = Users::where('id', decrypt($user_id))->first();
            return view('admin.DaftarPengguna.view', $this->data, compact('user'));

        } catch (\Throwable $e) {
            return redirect()->route('daftar_pengguna.index')->with('error_view', 'Halaman tidak tersedia, pastikan user terdaftar dan belum dihapus!');
        }
    }

    public function saveUpdate($user_id, Request $request)
    {
        //dd($request->all(), $user_id);
        try {
            if ($request->isMethod('put')) {

                $user = User::find($user_id);

                if (Hash::needsRehash($request->password)) {
                    $hashedPassword = Hash::make($request->password);
                } else {
                    // Jika password sudah menggunakan algoritma yang sesuai, gunakan yang sudah ada
                    $hashedPassword = $request->password;
                }

                // masukkan tanggal email_verified_at saat posisi user inactive tetapi sudah diaktifkan dari admin
                if ($user->status == 'inactive') {
                    if ($request->status == 'active') {
                        $user->update([
                            'email_verified_at' => now(),
                        ]);
                    }
                }

                // Lakukan update dengan password yang sudah di-rehash jika perlu
                $user->update([
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                    'status' => $request->status,
                    'password' => $hashedPassword,
                ]);

                Activity::create(array_merge(session('myActivity'), [
                    'user_id' => Auth::user()->id,
                    'action' => Auth::user()->username . ' Update User ID ' . $user->id,
                ]));

                return redirect()->route('daftar_pengguna.index')->with('success_submit_save', 'Data berhasil diupdate!');

            } else {
                return redirect()->back()->with('error_submit_save', 'Request data tidak valid!');
            }

        } catch (\Throwable $e) {
            return redirect()->back()->with('error_submit_save', 'Data gagal diupdate. ' . $e->getMessage());
        }
    }

    public function updatePassword($user_id)
    {
        $user = Users::where('id', decrypt($user_id))->first();
        return view('admin.DaftarPengguna.updatePassword', $this->data, compact('user'));
    }

    public function restore($user_id)
    {
        try {
            $user = User::withTrashed()->find(decrypt($user_id));
            $user->restore();
            $user->update(['status' => 'active']);

            Activity::create(array_merge(session('myActivity'), [
                'user_id' => Auth::user()->id,
                'action' => Auth::user()->username . ' Restore Data User Account ' . $user->username . ' ID ' . $user->id,
            ]));

            return redirect()->route('daftar_pengguna.index')->with('success_restore', 'Data berhasil direstore!');

        } catch (\Throwable $e) {
            return redirect()->route('daftar_pengguna.index')->with('error_restore', 'User id tidak ditemukan, pastikan user sudah di delete!');
        }

    }

    public function account_owner($user_id)
    {
        try {
            $settings = Settings::where('user_id', decrypt($user_id))->first();
            return view('admin.DaftarPengguna.account_owner', $this->data, compact('settings', 'user_id'));

        } catch (\Throwable $e) {
            return redirect()->route('daftar_pengguna.index')->with('error_submit_save', 'User ID tidak valid! ' . $e->getMessage());
        }
    }
    public function saveAccountOwner(Request $request)
    {
        try {

            $check = Settings::where('user_id', decrypt($request->user_id))->first();

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
                        'user_id' => decrypt($request->user_id),
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

                $user = User::find($request->user_id);

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
                    'action' => Auth::user()->username . ' Update Setting Account ' . ' ID ' . $check->id,
                ]));
            });

            return redirect()->back()->with('success_saved', 'Data berhasil disimpan!');

        } catch (\Throwable $e) {
            return redirect()->back()->with('error_saved', 'Data gagal disimpan. ' . $e->getMessage());
        }
    }
}