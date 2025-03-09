@php
    $login = getContent('login.content', true);
    if (session()->has('username')) {
        $username = session()->get('username');
    }
@endphp

@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="account py-60 section-gap-heading">
        <div class="account-inner">
            <div class="container">
                <div class="row gy-4 flex-wrap-reverse">
                    <div class="col-lg-6 d-lg-block d-none">
                        <div class="account-thumb">
                            <img src="{{ getImage('assets/images/frontend/login/' . @$login->data_values->image, '636x565') }}" alt="">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="account-form">
                            <div class="account-form__content mb-4">
                                <h4 class="account-form__title mb-2 "> {{ __(@$login->data_values->heading) }} </h4>
                            </div>
                            <form class="verify-gcaptcha" method="POST" action="{{ route('user.login') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-12 form-group">
                                        <label class="form--label" for="email">@lang('Username Or Email')</label>
                                        <input class="form--control" id="email" name="username" type="text" value="{{ old('username', @$username) }}" required>
                                    </div>
                                    <div class="col-sm-12 form-group">
                                        <label class="form--label" for="your-password">@lang('Password')</label>
                                        <div class="position-relative">
                                            <input class="form--control" id="your-password" name="password" type="password" required>
                                            <span class="password-show-hide fas fa-eye toggle-password fa-eye-slash" id="#your-password"></span>
                                        </div>
                                    </div>

                                    <x-captcha />

                                    <div class="col-sm-12 form-group">
                                        <div class="d-flex flex-wrap justify-content-between">
                                            <div class="form--check">
                                                <input class="form-check-input" id="remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="remember">@lang('Remember me') </label>
                                            </div>
                                            <a class="forgot-password text--base" href="{{ route('user.password.request') }}">@lang('Forgot your password?')</a>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 form-group">
                                        <button class="btn btn--base w-100" id="recaptcha" type="submit">@lang('Sign in')</button>
                                    </div>
                                    @include($activeTemplate . 'user.auth.social_login')
                                    <div class="col-sm-12">
                                        <div class="have-account">
                                            <p class="have-account__text">@lang('Don\'t have an account?') <a class="have-account__link text--base" href="{{ route('user.register') }}">@lang('Sign Up')</a> @lang('now')</p>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
