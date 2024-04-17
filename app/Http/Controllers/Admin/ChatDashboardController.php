<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatDashboard;

class ChatDashboardController extends Controller
{
    private $data;

    public function __construct()
    {
        $this->data['currentAdminMenu'] = 'chat_dashboard';
    }

    public function index()
    {
        $chats = ChatDashboard::all();
        //dd($chat);
        return view('admin.ChatDashboard.index', $this->data, compact('chats'));
    }
}