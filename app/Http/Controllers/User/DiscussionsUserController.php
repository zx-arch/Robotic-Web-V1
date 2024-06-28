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
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class DiscussionsUserController extends Controller
{
    public function index()
    {
        $discussions = Discussions::leftJoin('discussions_answer', function ($join) {
            $join->on('discussions.id', '=', 'discussions_answer.discussion_id')
                ->whereNull('discussions_answer.reply_user_id');
        })
            ->leftJoin('users', 'users.id', '=', 'discussions.user_id')
            ->select(
                'discussions.id',
                'discussions.title',
                'discussions.message',
                'discussions.created_at',
                'discussions.updated_at',
                'discussions.user_id',
                'discussions.views',
                'discussions.likes',
                'users.username',
                DB::raw('COUNT(discussions_answer.id) as responses')
            )
            ->groupBy('discussions.id', 'discussions.title', 'discussions.message', 'discussions.created_at', 'discussions.updated_at', 'discussions.user_id', 'discussions.views', 'discussions.likes', 'users.username')
            ->latest()->paginate(10);

        return view('user.Discussions.index', compact('discussions'));
    }

    public function filter(Request $request)
    {
        $filterData = $request->input('filter', []);
        $title = $filterData['title'] ?? null;
        $filterOption = $filterData['filter_option'] ?? 'created_at';
        $sorting = $filterData['sorting'] ?? 'asc';

        $validFilterOptions = ['title', 'created_at', 'likes', 'views', 'responses']; // Pastikan kolom valid ada di sini
        if (!in_array($filterOption, $validFilterOptions)) {
            $filterOption = 'created_at'; // Default jika filter_option tidak valid
        }

        $discussions = Discussions::leftJoin('discussions_answer', function ($join) {
            $join->on('discussions.id', '=', 'discussions_answer.discussion_id')
                ->whereNull('discussions_answer.reply_user_id');
        })
            ->leftJoin('users', 'users.id', '=', 'discussions.user_id')
            ->select(
                'discussions.id',
                'discussions.title',
                'discussions.message',
                'discussions.created_at',
                'discussions.updated_at',
                'discussions.user_id',
                'discussions.views',
                'discussions.likes',
                'users.username',
                DB::raw('COUNT(discussions_answer.id) as responses')
            )
            ->groupBy('discussions.id', 'discussions.title', 'discussions.message', 'discussions.created_at', 'discussions.updated_at', 'discussions.user_id', 'discussions.views', 'discussions.likes', 'users.username');

        if ($title !== null) {
            $discussions->where('discussions.title', 'like', "%$title%");
        }

        $discussions->orderBy($filterOption, $sorting);

        $itemsPerPage = 10;
        $discussions = $discussions->paginate($itemsPerPage);

        $fullUri = $request->getRequestUri();

        $discussions->setPath($fullUri);

        return view('user.Discussions.index', compact('discussions'));
    }
    public function responseNotif($id, $title, $id_notif)
    {
        $checkNotif = Notification::where('id', $id_notif)->first();

        if ($checkNotif) {
            $checkNotif->update([
                'read' => true,
                'date_read' => now()
            ]);
        }

        if (Str::contains($checkNotif->redirect, 'discussions')) {
            return redirect()->route('user.discussions.getByID', ['id' => $id, 'title' => $title]);

        } else {
            return redirect($checkNotif->redirect);
        }
    }

    public function getByID($id, $title)
    {

        $discussion = Discussions::select('discussions.*', 'users.username as created_by', 'likes_discussion.user_id', 'likes_discussion.is_clicked_like')
            ->leftJoin('likes_discussion', 'likes_discussion.discussion_id', '=', 'discussions.id')
            ->leftJoin('users', 'users.id', '=', 'discussions.user_id')
            ->where('discussions.id', $id)->first();

        if (!$discussion) {
            return redirect()->intended(route('user.discussions'));
        }

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

        $answers = DiscussionsAnswer::select('discussions_answer.*', 'users.username')->leftJoin('users', 'users.id', '=', 'discussions_answer.user_id')
            ->with([
                'replies' => function ($query) {
                    $query->select('discussions_answer.*', 'users.username')
                        ->leftJoin('users', 'users.id', '=', 'discussions_answer.user_id')
                        ->orderBy('created_at', 'asc');
                }
            ])
            ->where('discussions_answer.discussion_id', $id)
            ->whereNull('discussions_answer.reply_user_id')
            ->latest()
            ->get();

        $discussionStats = DiscussionsAnswer::where('discussion_id', $id)
            ->selectRaw('COUNT(*) as total_answers')->whereNull('reply_user_id')
            ->first();

        return view('user.Discussions.findID', compact('discussion', 'time_difference', 'discussionStats', 'answers'));
    }

    public function saveAnswer(Request $request)
    {
        DB::transaction(function () use ($request) {
            // Mengambil pemilik diskusi yang dibuat
            $discussion = Discussions::where('id', $request->discussion_id)->lockForUpdate()->first();
            if (!$discussion) {
                throw new \Exception('Created by discussion not found');
            }

            // Memproses gambar jika ada
            if ($request->hasFile('gambar')) {
                $directory = public_path('discussions/comment/gambar/');
                $uniqueImageName = time() . '_' . $request->file('gambar')->getClientOriginalName();

                // Membuat direktori jika tidak ada
                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }

                // Simpan file gambar ke direktori yang diinginkan
                $request->file('gambar')->move($directory, $uniqueImageName);
            }

            // Membuat jawaban diskusi
            DiscussionsAnswer::create([
                'discussion_id' => $discussion->id,
                'user_id' => Auth::user()->id,
                'message' => $request->message,
                'gambar' => $request->hasFile('gambar') ? url('discussions/comment/gambar/' . $uniqueImageName) : null,
            ]);

            if ($discussion->user_id != Auth::user()->id) {
                Notification::create([
                    'user_id' => $discussion->user_id,
                    'title' => 'Other User Comment Your Post Discussion',
                    'content' => Auth::user()->username . ' membalas postingan diskusi anda "' . $discussion->title . '"',
                    'redirect' => route('user.discussions.getByID', ['id' => $discussion->id, 'title' => str_replace(' ', '-', str_replace('?', '', strtolower($discussion->title)))])
                ]);
            }
        });

        return redirect()->back();
    }

    public function saveReply(Request $request)
    {
        DB::transaction(function () use ($request) {
            // Validasi request
            $validatedData = $request->validate([
                'discussion_id' => 'required',
                'message' => 'required',
                'answer_id' => 'required'
            ]);

            // Mengunci record discussion untuk update
            $discussion = Discussions::where('id', $validatedData['discussion_id'])->lockForUpdate()->first();

            if (!$discussion) {
                throw new \Exception('Discussion not found');
            }

            // Membuat jawaban diskusi
            DiscussionsAnswer::create([
                'discussion_id' => $discussion->id,
                'user_id' => Auth::user()->id,
                'message' => $validatedData['message'],
                'reply_user_id' => $validatedData['answer_id']
            ]);

            $pushOtherUser = User::whereIn('id', DiscussionsAnswer::get()->pluck('user_id'))
                ->where('role', '=', Auth::user()->role)
                ->pluck('id');

            foreach ($pushOtherUser as $user_all) {
                if ($user_all != Auth::user()->id) {
                    Notification::create([
                        'user_id' => $user_all,
                        'title' => 'Reply Comment Your Post Discussion',
                        'content' => Auth::user()->username . ' membalas komentar "' . $validatedData['message'] . '" postingan diskusi anda "' . $discussion->title . '"',
                        'redirect' => route('user.discussions.getByID', ['id' => $discussion->id, 'title' => str_replace(' ', '-', str_replace('?', '', strtolower($discussion->title)))])
                    ]);
                }
            }

        });

        return redirect()->back();
    }

    public function answerLike(Request $request, $discuss_id, $answer_id)
    {
        $answer = null;
        $liked = false;

        DB::transaction(function () use ($request, $discuss_id, $answer_id, &$answer, &$liked) {

            $answer = DiscussionsAnswer::where('discussion_id', $discuss_id)->where('id', $answer_id)->lockForUpdate()->first();
            $liked = $request->input('liked');

            if ($answer->is_clicked_like) {
                $answer->like -= 1;
                $liked = false;
                $answer->is_clicked_like = false;

                $infoDiscussion = Discussions::where('id', $answer->discussion_id)->lockForUpdate()->first();

                $notif = Notification::create([
                    'user_id' => $answer->user_id,
                    'title' => 'Another User Like Your Comment',
                    'content' => 'Seseorang menyukai komentar anda "' . $answer->message . '"',
                    'redirect' => route('user.discussions.getByID', ['id' => $infoDiscussion->discussion_id, 'title' => str_replace(' ', '-', str_replace('?', '', strtolower($infoDiscussion->title)))])
                ]);

            } else {
                $answer->like += 1;
                $liked = true;
                $answer->is_clicked_like = true;
                $notification = Notification::where('user_id', $answer->user_id)
                    ->where('content', 'Seseorang menyukai komentar anda "' . $answer->message . '"')
                    ->first();

                if ($notification) {
                    $notification->forceDelete();
                }
            }

            $answer->save();

        });

        // Return the new like count and whether the user has liked it
        return response()->json(['likeCount' => $answer->like, 'liked' => $liked]);
    }

    public function processLike($id)
    {
        try {
            $user_id = Auth::user()->id;
            $addBg = '';
            $removeBg = '';
            $discussion = null;

            DB::transaction(function () use ($id, $user_id, &$addBg, &$removeBg, &$discussion) {

                $discussion = Discussions::lockForUpdate()->findOrFail($id);

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
                    $addBg = 'btn-primary';
                    $removeBg = 'btn-light';

                } else {
                    // Toggle is_clicked_like
                    $check->update([
                        'is_clicked_like' => !$check->is_clicked_like
                    ]);

                    // Sesuaikan jumlah likes berdasarkan is_clicked_like terbaru
                    if ($check->is_clicked_like) {
                        $discussion->likes++;
                        $addBg = 'btn-primary';
                        $removeBg = 'btn-light';
                    } else {
                        $addBg = 'btn-light';
                        $removeBg = 'btn-primary';
                        $discussion->likes--;
                    }
                }

                // Pastikan likes tidak kurang dari 0
                if ($discussion->likes < 0) {
                    $discussion->likes = 0;
                }

                // Simpan perubahan jumlah likes di discussion
                $discussion->save();
            });

            // Kirim response JSON dengan jumlah likes terbaru
            return response()->json([
                'likes' => $discussion->likes,
                'bg' => [
                    'add' => $addBg,
                    'remove' => $removeBg
                ]
            ]);

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
            $validator = Validator::make($request->all(), [
                'hashtags' => [
                    'required',
                    'string',
                    'regex:/^(#\w+(\s+#\w+)*)+$/',
                ],
                'title' => [
                    'required',
                    'string',
                    'regex:/^\s*(\S+\s+){2,}\S+\s*$/u', // minimal 3 kata
                    'max:255',
                ],
                'message' => 'required|string|min:20',
                'gambar' => 'nullable|image|mimes:png,jpeg,jpg|max:512',
            ], [
                'hashtags.regex' => 'Hashtags tidak valid! Format yang benar cth: #tag1 #tag2',
                'title.regex' => 'Judul harus terdiri dari minimal 3 kata.',
                'title.max' => 'Judul tidak boleh lebih dari 255 karakter.',
                'message.min' => 'Pesan harus memiliki minimal 20 karakter.',
                'gambar.image' => 'File harus berupa gambar (format JPEG/PNG).',
                'gambar.mimes' => 'Format gambar yang diperbolehkan adalah JPEG atau PNG.',
                'gambar.max' => 'Ukuran gambar tidak boleh lebih dari 512 KB.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            DB::transaction(function () use ($request) {
                $newDiscuss = Discussions::create([
                    'user_id' => Auth::user()->id,
                    'title' => $request->title,
                    'message' => $request->message,
                    'hashtags' => json_encode(explode(' ', $request->hashtags)),
                ]);

                if ($request->hasFile('gambar')) {
                    $directory = public_path('discussions/gambar/');
                    $uniqueImageName = time() . '_' . $request->file('gambar')->getClientOriginalName();

                    if (!file_exists($directory)) {
                        mkdir($directory, 0777, true);
                    }

                    $request->file('gambar')->move($directory, $uniqueImageName);

                    Discussions::where('id', $newDiscuss->id)->lockForUpdate()->update([
                        'gambar' => url('discussions/gambar/' . $uniqueImageName),
                    ]);
                }

                $pushOtherUser = User::whereNotIn('id', [Auth::id()])
                    ->where('role', '=', Auth::user()->role)
                    ->lockForUpdate()
                    ->pluck('id');

                foreach ($pushOtherUser as $other_user_id) {
                    Notification::create([
                        'user_id' => $other_user_id,
                        'title' => Auth::user()->username . ' Menambahkan Diskusi "' . $request->title . '"',
                        'content' => $request->message,
                        'redirect' => route('user.discussions.getByID', ['id' => $newDiscuss->id, 'title' => str_replace(' ', '-', str_replace('?', '', strtolower($request->title)))])
                    ]);
                }

                $hashtags = explode(' ', $request->hashtags);

                foreach ($hashtags as $hashtag) {
                    Hashtags::firstOrCreate([
                        'tag_name' => $hashtag,
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