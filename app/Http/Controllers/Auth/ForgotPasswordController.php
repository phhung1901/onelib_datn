<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\ResetUserPassword as ResetPasswordNotification;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @throws \Exception
     */
    public function getUser(array $credentials)
    {
        $credentials = Arr::except($credentials, ['token']);

        $user = app(PasswordBroker::class)->getUser($credentials);

        if ($user && ! $user instanceof CanResetPasswordContract) {
            throw new \Exception('User must implement CanResetPassword interface.');
        }

        return $user;
    }
    public function showResetForm(Request $request)
    {
        $token = $request->route()->parameter('token');

        return view('frontend_v4.auth.forgot-password')->with(
            ['token' => $token, 'email' => $request->input('email')]
        );
    }


    /**
     * @throws \Exception
     */
    public function sendEmail(Request $request)
    {
        $this->validateEmail($request);

        $response = $this->sendResetLink(
            $this->credentials($request)
        );

        return $response
            ? $this->sendResetLinkResponse($request, $response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }


    /**
     * @throws \Exception
     */
    public function sendResetLink(array $credentials)
    {

        $user = $this->getUser($credentials);

        if (is_null($user)) {
            return "We can't find a user with that email address.";
        }

        $user->notify(new ResetPasswordNotification(app(PasswordBroker::class)->createToken($user)));

        return "We have sent you an email with a link to reset your password!";
    }

}
