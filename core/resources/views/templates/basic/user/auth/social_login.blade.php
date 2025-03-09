@php
    $credentials = $general->socialite_credentials;
@endphp
@if ($credentials->google->status == Status::ENABLE || $credentials->facebook->status == Status::ENABLE || $credentials->linkedin->status == Status::ENABLE)
    <div class="col-12 form-group">
        <span class="social-or">@lang('OR')</span>
    </div>
    <div class="col-12 form-group">
        <ul class="social-login">
            @if ($credentials->facebook->status == Status::ENABLE)
                <li class="login-item">
                    <a class="login-link flex-center align-items-center w-100 flex-align gap-2" href="{{ route('user.social.login', 'facebook') }}">
                        <span class="icon-facebook"><i class="fab fa-facebook-f"></i></span>
                        <p class="text">
                            @lang('Facebook')
                        </p>
                    </a>
                </li>
            @endif
            @if ($credentials->google->status == Status::ENABLE)
                <li class="login-item">
                    <a class="login-link flex-center align-items-center w-100 flex-align gap-2" href="{{ route('user.social.login', 'google') }}">
                        <span class="icon-google"><i class="fab fa-google"></i></span>
                        <p class="text">
                            @lang('Google')
                        </p>
                    </a>
                </li>
            @endif
            @if ($credentials->linkedin->status == Status::ENABLE)
                <li class="login-item">
                    <a class="login-link flex-center align-items-center w-100 flex-align gap-2" href="{{ route('user.social.login', 'linkedin') }}">
                        <span class="icon-linkedin"><i class="fab fa-linkedin-in"></i></span>
                        <p class="text">
                            @lang('linkdin')
                        </p>
                    </a>
                </li>
            @endif
        </ul>
    </div>
@endif
