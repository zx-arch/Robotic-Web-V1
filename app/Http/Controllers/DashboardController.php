<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\ActivityRepositoryInterface;
use App\Models\ChatDashboard;
use Illuminate\Support\Facades\Session;
use GeoIp2\Database\Reader;
use App\Models\Activity;
use App\Models\Tutorials;
use App\Events\NotifyProcessed;

class DashboardController extends Controller
{
    private $data;
    protected $activityRepository;

    public function __construct(ActivityRepositoryInterface $activityRepository)
    {
        $this->data['currentActive'] = 'dashboard';
        $this->activityRepository = $activityRepository;
    }

    public function index(Request $request)
    {
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

                            $this->activityRepository->create([
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
}