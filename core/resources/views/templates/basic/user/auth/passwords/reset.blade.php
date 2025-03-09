@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="account py-120">
        <div class="account-inner">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-7 col-xl-5">
                        <div class="account-form">
                                <h4 class="pb-3  border-bottom">@lang('Reset Password')</h4>
                                <div class="mb-4">
                                    <p>@lang('Your account is verified successfully. Now you can change your password. Please enter a strong password and don\'t share it with anyone.')</p>
                                </div>
                                <form method="POST" action="{{ route('user.password.update') }}">
                                    @csrf
                                    <input name="email" type="hidden" value="{{ $email }}">
                                    <input name="token" type="hidden" value="{{ $token }}">
                                    <div class="form-group">
                                        <label class="form--label" for="your-password">@lang('Password')</label>
                                        <div class="position-relative">
                                            <input class="form--control @if ($general->secure_password) secure-password @endif" id="your-password" name="password" type="password" required>
                                            <span class="password-show-hide fas fa-eye toggle-password fa-eye-slash" id="#your-password"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form--label" for="confirm-password">@lang('Confirm Password')</label>
                                        <div class="position-relative">
                                            <input class="form--control" id="confirm-password" name="password_confirmation" type="password" required>
                                            <div class="password-show-hide fas fa-eye toggle-password fa-eye-slash" id="#confirm-password"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn--base w-100" type="submit"> @lang('Submit')</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@if ($general->secure_password)
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif
