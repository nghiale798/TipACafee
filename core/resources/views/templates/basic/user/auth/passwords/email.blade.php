@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="account py-120">
        <div class="account-inner">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-7 col-xl-5">
                        <div class="account-form">
                            <h4 class="pb-3  border-bottom">{{ __($pageTitle) }}</h4>
                            <div class="mb-3">
                                <p>@lang('To recover your account please provide your email or username to find your account.')</p>
                            </div>
                            <form class="verify-gcaptcha" method="POST" action="{{ route('user.password.email') }}">
                                @csrf
                                <div class="form-group">
                                    <label class="form--label">@lang('Username or Email')</label>
                                    <input class="form--control" name="value" type="text" value="{{ old('value') }}" required autofocus="off">
                                </div>

                                <x-captcha />

                                <div class="form-group mb-0">
                                    <button class="btn btn--base w-100" type="submit">@lang('Submit')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
