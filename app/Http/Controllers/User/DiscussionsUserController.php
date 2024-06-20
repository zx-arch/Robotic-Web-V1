<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Discussions;
use App\Models\Hashtags;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


class DiscussionsUserController extends Controller
{
    public function index()
    {
        $discussions = Discussions::select('discussions.*', 'users.username')->leftJoin('users', 'users.id', '=', 'discussions.user_id')->with('user')->latest()->get();

        return view('user.Discussions.index', compact('discussions'));
    }

    public function add()
    {
        $hashtags = json_encode(Hashtags::get());
        return view('user.Discussions.add', compact('hashtags'));
    }

    public function saveAdd(Request $request)
    {
        try {
            $request->validate([
                'hashtags' => [
                    'required',
                    'string',
                    'regex:/^(#(\w+)(\s+#\w+)*)+$/',
                ],
            ], [
                'hashtags.regex' => 'Hastags tidak valid!',
            ]);

            $newDiscuss = Discussions::create([
                'user_id' => Auth::user()->id,
                'title' => $request->title,
                'message' => $request->message,
                'hashtags' => json_encode(explode(' ', $request->hashtags)),
            ]);

            if ($request->hasFile('gambar')) { // Periksa apakah file gambar dikirimkan

                $directory = public_path('discussions/gambar/');
                $imageExtension = $request->file('gambar')->getClientOriginalExtension();
                $uniqueImageName = time() . '_' . $request->file('gambar')->getClientOriginalName();

                // Membuat direktori jika tidak ada
                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }

                // Simpan data image ke dalam file di direktori yang diinginkan
                $request->file('gambar')->move(public_path('discussions/gambar/'), $uniqueImageName);

                Discussions::where('id', $newDiscuss->id)->update([
                    'gambar' => $uniqueImageName,
                ]);

            }

            $hastags = explode(' ', $request->hashtags);

            foreach ($hastags as $hastag) {
                Hashtags::create([
                    'tag_name' => $hastag,
                ]);
            }
            return redirect()->route('user.discussions')->with('success_saved', 'Data has been successfully saved!');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }
}