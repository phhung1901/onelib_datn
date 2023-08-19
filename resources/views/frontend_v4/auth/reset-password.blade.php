<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="none">
    <link rel="stylesheet" href="{{ asset('assets_v4/css/auth.css') }}">

    <title>Reset password</title>

</head>
<body>
<div class="container">
    <div id="log-in">
        <form class="login-form" action="{{ route('frontend.auth.password.post.change_pass') }}" method="post">
            {{ csrf_field() }}
            @if(!empty($token))
                <input type="hidden" name="token" value="{{ $token }}">
            @endif

            <div class="content">
                <div class="welcome-text">
                    <a href="{{ route('document.home.index') }}">
                        <img src="{{ asset('assets_v4/images/libshare-png-2.png') }}" class="logo">
                    </a>
                </div>

                {{ csrf_field() }}
                @if(!empty($token))
                    <input type="hidden" name="token" value="{{ $token }}">
                @endif

                <div class="field-container -username">
                    <input type="email" name="email" value="{{$email ?? ''}}" placeholder="E-Mail Address"/>
                </div>

                <div class="field-container -username">

                    <input type="password" name="password" placeholder="New password"/>
                </div>

                <div class="field-container -username">
                    <input type="password" name="password_confirmation"
                           placeholder="New password confirmation"/>
                </div>

                @if ($errors->has('email'))
                    <div class="errors">
                        <i>{{ $errors->first('email') }}</i>
                    </div>
                @endif

                @if ($errors->has('password'))
                    <div class="errors">
                        <i>{{ $errors->first('password') }}</i>
                    </div>
                @endif
                @if ($errors->has('password_confirmation'))
                    <div class="errors">
                        <i>{{ $errors->first('password_confirmation') }}</i>
                    </div>
                @endif

                <div class="register-container">
                    <a class="forgot-password" href="{{ route('frontend.auth.getLogin') }}">Login</a>
                </div>
                <button class="log-in-button" type="submit">Reset password</button>
                <div class="social-login-separator"><span>or</span></div>

                <div class="social-login-buttons">
                    <a class="facebook-login ripple-effect" rel="nofollow"
                       href="{{ route('frontend.login.facebook') }}">
                        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2"
                             fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                        </svg>
                        <p>Log in using Facebook</p>
                    </a>
                    <a class="google-login ripple-effect"
                       rel="nofollow"
                       href="{{ route('frontend.login.google') }}">
                        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2"
                             fill="none"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <p>Log in using Google</p>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Client IP {{\Request::ip()}} -->
</body>
</html>
