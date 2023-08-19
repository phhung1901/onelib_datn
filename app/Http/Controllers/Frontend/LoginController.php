<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function getLogin()
    {
        if (Auth::check()) {
            // nếu đăng nhập thàng công thì
            return redirect(route('document.home.index'));
        } else {
            return view('frontend_v4.auth.login');
        }
    }

    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required',
        ]);

        if (Auth::attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ])) {
            return redirect('/');
        }
        Session::flash('error', 'These credentials do not match our records.');
        return redirect()->back()->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->back();
    }


}
