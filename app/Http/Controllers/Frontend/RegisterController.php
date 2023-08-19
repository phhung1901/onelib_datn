<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\RegisterRequest;
use App\Models\User;
use Backpack\PermissionManager\app\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

/**
 * @method guard()
 */
class RegisterController extends Controller
{
    public function getRegister(){
//        if (Auth::check()) {
//            return redirect(route('document.home.index'));
//        } else {
            return view('frontend_v4.auth.register');
//        }
    }

    public function postRegister(RegisterRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user){
            return redirect()->back()->withErrors(['email' => 'Email already exists'])->withInput($request->only(['email', 'name']));
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $role = Role::where('name', 'User')->first();

        $user->assignRole($role);

        Auth::login($user);
//        Session::flash('error', 'These credentials do not match our records.');
        return redirect()->route('document.home.index');
    }
}
