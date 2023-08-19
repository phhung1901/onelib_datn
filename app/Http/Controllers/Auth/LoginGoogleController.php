<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class LoginGoogleController extends Controller
{
    public function index(){
        try {

            $user = Socialite::driver('google')->user();
            $existed = User::where('email', $user->email)->first();

            if($existed){

                Auth::login($existed);

                return redirect()->route('document.home.index');

            }else{
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'social_id'=> $user->id,
                    'avatar' => $user->avatar,
                    'social_type'=> 'google',
                    'password' => bcrypt('my-google'),
                    'created_at' => new DateTime(),
                    'updated_at' => new DateTime(),
                ]);
                Auth::login($newUser);
                return redirect()->route('document.home.index');
            }

        } catch (\Exception $e) {
            Session::flash('error', 'Login google failed!');

            return redirect()->route('frontend.auth.getLogin');
        }
    }
}
