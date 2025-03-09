@extends($activeTemplate . 'layouts.master')
@section('content')
<h2 class="page-title">{{ __($pageTitle) }}</h2>
    <div class="multi-page-card">
        <div class="multi-page-card__body">
            <div class="row justify-content-center gy-4">
                @if (!auth()->user()->ts)
                    <div class="col-md-10">
                        <h5 class="page-title">@lang('Add Your Account')</h5>
                        <div>
                            <h6 class="mb-3">
                                @lang('Use the QR code or setup key on your Google Authenticator app to add your account. ')
                            </h6>
                            <div class="form-group mx-auto text-center">
                                <img class="mx-auto" src="{{ $qrCodeUrl }}" alt="">
                            </div>
                            <div class="form-group">
                                <label class="form-label">@lang('Setup Key')</label>
                                <div class="copy-link input-group mt-3">
                                    <input class="form-control form--control" type="text" value="{{ $secret }}" disabled>
                                    <button class="input-group-text flex-align copy-btn fs-14 " id="copyBtn" data-link="{{ $secret }}" type="button"><i class="far fa-copy"></i>&nbsp; @lang('Copy')</button>
                                </div>
                            </div>
                            <label><i class="fa fa-info-circle"></i> @lang('Help')</label>
                            <p>@lang('Google Authenticator is a multifactor app for mobile devices. It generates timed codes used during the 2-step verification process. To use Google Authenticator, install the Google Authenticator application on your mobile device.') <a class="text--base" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en" target="_blank">@lang('Download')</a></p>
                        </div>
                    </div>
                @endif
                <div class="col-md-10">
                    <hr>
                    @if (auth()->user()->ts)
                        <h5>@lang('Disable 2FA Security')</h5>
                        <form action="{{ route('user.twofactor.disable') }}" method="POST">
                            <div>
                                @csrf
                                <input name="key" type="hidden" value="{{ $secret }}">
                                <div class="form-group">
                                    <label class="form-label">@lang('Google Authenticatior OTP')</label>
                                    <input class="form-control form--control" name="code" type="text" required>
                                </div>
                                <button class="btn btn--base w-100" type="submit">@lang('Submit')</button>
                            </div>
                        </form>
                    @else
                        <h5>@lang('Enable 2FA Security')</h5>
                        <form action="{{ route('user.twofactor.enable') }}" method="POST">
                            <div>
                                @csrf
                                <input name="key" type="hidden" value="{{ $secret }}">
                                <div class="form-group">
                                    <label class="form-label">@lang('Google Authenticatior OTP')</label>
                                    <input class="form-control form--control" name="code" type="text" required>
                                </div>
                                <button class="btn btn--base w-100" type="submit">@lang('Submit')</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .copied::after {
            background-color: #{{ $general->base_color }};
        }
    </style>
@endpush
