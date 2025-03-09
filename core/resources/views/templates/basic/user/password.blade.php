@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="multi-page-card">
        <div class="multi-page-card__body">
            <div class="row">
                <div class="col-12">
                    <div class="section-heading complete-profile-heading">
                        <h5 class="section-heading__title">{{ __($pageTitle) }}</h5>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-10">
                    <form action="" method="post">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">@lang('Current Password')</label>
                            <input class="form-control form--control" name="current_password" type="password" required autocomplete="current-password">
                        </div>
                        <div class="form-group">
                            <label class="form-label">@lang('Password')</label>
                            <input class="form-control form--control @if ($general->secure_password) secure-password @endif" name="password" type="password" required autocomplete="current-password">
                        </div>
                        <div class="form-group">
                            <label class="form-label">@lang('Confirm Password')</label>
                            <input class="form-control form--control" name="password_confirmation" type="password" required autocomplete="current-password">
                        </div>
                        <div class="form-group">
                            <button class="btn btn--base w-100" type="submit">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@if ($general->secure_password)
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif
