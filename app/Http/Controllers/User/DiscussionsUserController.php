<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Discussions;
use App\Models\DiscussionsAnswer;
use App\Models\LikesDiscussion;
use App\Models\User;
use App\Models\Hashtags;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class DiscussionsUserController extends Controller
{
    public function index()
    {
        $discussions = Discussions::select('discussions.*', 'users.username')->leftJoin('users', 'users.id', '=', 'discussions.user_id')->with('user')->latest()->get();

        return view('user.Discussions.index', compact('discussions'));
    }

    public function getByID($id, $title)
    {
        $discussion = Discussions::find($id);
        $created_at = Carbon::parse($discussion->created_at);
        $updated_at = Carbon::parse($discussion->updated_at);

        // Check if this user has already viewed this discussion in this session
        $sessionKey = 'discussion_' . $discussion->id . '_viewed';
        if (!Session::has($sessionKey)) {
            // Increment views count and mark as viewed in session
            $discussion->incrementViewCount();
            Session::put($sessionKey, true);
        }

        // Hitung selisih waktu
        $now = Carbon::now()->startOfDay();
        $updated_at_midnight = $updated_at->startOfDay();

        // Hitung selisih dalam hari
        $days_difference = $updated_at_midnight->diffInDays($now);

        // Tentukan teks berdasarkan selisih hari
        if ($days_difference == 0) {
            $time_difference = 'today';
        } elseif ($days_difference == 1) {
            $time_difference = 'yesterday';
        } else {
            $time_difference = $days_difference . ' days ago';
        }

        $checkLike = LikesDiscussion::where('user_id', Auth::user()->id)->first();

        if ($checkLike) {
            $checkLike->update([
                'is_clicked_like' => false
            ]);
        }

        $answers = DiscussionsAnswer::select('discussions_answer.*', 'users.username')->leftJoin('users', 'users.id', '=', 'discussions_answer.user_id')
            ->where('discussions_answer.discussion_id', $id)
            ->with([
                'replies' => function ($query) {
                    $query->select('discussions_answer.*', 'users.username')
                        ->leftJoin('users', 'users.id', '=', 'discussions_answer.user_id')
                        ->orderBy('created_at', 'asc');
                }
            ])
            ->whereNull('discussions_answer.reply_user_id')
            ->latest()
            ->get();

        $discussionStats = DiscussionsAnswer::where('discussion_id', $id)
            ->selectRaw('COUNT(*) as total_answers')->whereNull('reply_user_id')
            ->first();

        return view('user.Discussions.findID', compact('discussion', 'time_difference', 'checkLike', 'discussionStats', 'answers'));
    }

    public function saveAnswer(Request $request)
    {
        if ($request->hasFile('gambar')) { // Periksa apakah file gambar dikirimkan

            $directory = public_path('discussions/coment/gambar/');
            $uniqueImageName = time() . '_' . $request->file('gambar')->getClientOriginalName();

            // Membuat direktori jika tidak ada
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            // Simpan data image ke dalam file di direktori yang diinginkan
            $request->file('gambar')->move(public_path('discussions/coment/gambar/'), $uniqueImageName);
        }

        DiscussionsAnswer::create([
            'discussion_id' => $request->discussion_id,
            'user_id' => Auth::user()->id,
            'message' => $request->message,
            'gambar' => (($request->has('gambar')) ? $uniqueImageName : null)
        ]);

        return redirect()->back();
    }

    public function saveReply(Request $request)
    {
        DiscussionsAnswer::create([
            'discussion_id' => $request->discussion_id,
            'user_id' => Auth::user()->id,
            'message' => $request->message,
            'reply_user_id' => $request->answer_id
        ]);
        return redirect()->back();
    }

    public function answerLike(Request $request, $discuss_id, $answer_id)
    {
        $answer = DiscussionsAnswer::where('discussion_id', $discuss_id)->where('id', $answer_id)->first();
        $liked = $request->input('liked');

        if ($answer->is_clicked_like) {
            $answer->like -= 1;
            $liked = false;
            $answer->is_clicked_like = false;
        } else {
            $answer->like += 1;
            $liked = true;
            $answer->is_clicked_like = true;
        }

        $answer->save();

        // Return the new like count and whether the user has liked it
        return response()->json(['likeCount' => $answer->like, 'liked' => $liked]);
    }

    public function processLike(Request $request, $id)
    {
        try {
            $discussion = Discussions::findOrFail($id);
            $user_id = Auth::user()->id;

            // Cek apakah pengguna sudah melakukan "like" sebelumnya
            $check = LikesDiscussion::where('user_id', $user_id)
                ->where('discussion_id', $id)
                ->first();

            if (!$check) {
                // Jika belum ada "like", tambahkan ke tabel LikesDiscussion
                LikesDiscussion::create([
                    'discussion_id' => $id,
                    'user_id' => $user_id,
                    'is_clicked_like' => true
                ]);
                $discussion->likes++;

            } else {
                // Toggle is_clicked_like
                $check->update([
                    'is_clicked_like' => !$check->is_clicked_like
                ]);

                // Sesuaikan jumlah likes berdasarkan is_clicked_like terbaru
                if ($check->is_clicked_like) {
                    $discussion->likes++;
                } else {
                    $discussion->likes--;
                }
            }

            // Pastikan likes tidak kurang dari 0
            if ($discussion->likes < 0) {
                $discussion->likes = 0;
            }

            // Simpan perubahan jumlah likes di discussion
            $discussion->save();

            // Kirim response JSON dengan jumlah likes terbaru
            return response()->json(['likes' => $discussion->likes]);

        } catch (\Throwable $e) {
            // Tangkap dan kirim pesan error jika terjadi kesalahan
            return response()->json(['error' => $e->getMessage()]);
        }
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

            DB::transaction(function () use ($request) {

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

                $pushOtherUser = User::whereNotIn('id', [Auth::id()])->where('role', '=', Auth::user()->role)->pluck('id');

                foreach ($pushOtherUser as $other_user_id) {
                    Notification::create([
                        'user_id' => $other_user_id,
                        'title' => Auth::user()->username . ' Menambahkan Diskusi "' . $request->title . '"',
                        'content' => $request->message,
                        'redirect' => route('user.discussions.getByID', ['id' => $newDiscuss->id, 'title' => str_replace(' ', '-', str_replace('?', '', strtolower($request->title)))])
                    ]);
                }

                $hastags = explode(' ', $request->hashtags);

                foreach ($hastags as $hastag) {
                    Hashtags::create([
                        'tag_name' => $hastag,
                    ]);
                }
            });

            return redirect()->route('user.discussions')->with('success_saved', 'Data has been successfully saved!');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();

        } catch (QueryException $e) {
            return redirect()->route('user.discussions')->with('error_saved', 'Failed to save data. Please try again later.');
        }
    }
}