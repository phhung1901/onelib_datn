<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showResetForm(Request $request, $token = null){
        if (is_null($token)) {
            return view('frontend_v4.auth.forgot-password');
        }
        $email = $request->input('email');
        if (empty($email)) {
            return view('frontend_v4.auth.forgot-password');
        }
        return view('frontend_v4.auth.reset-password')->with(compact('token', 'email'));
    }
}
