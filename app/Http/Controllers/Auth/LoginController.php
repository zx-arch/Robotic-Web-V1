<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    private $data;
    public function __construct()
    {
        $this->data['currentActive'] = 'login';
    }
    public function index()
    {
        return view('login', $this->data);
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

            if ($user->role == 'admin') {
                return redirect()->route('admin.dashboard');

            } elseif ($user->role == 'pengurus') {
                return redirect()->route('pengurus.dashboard');

            } elseif ($user->role == 'user') {
                return redirect()->route('user.dashboard');

            } else {
                return redirect()->intended('/');
            }
        }

        return redirect()->back()->withErrors(['username_or_email' => 'These credentials do not match our records.']);
    }
}