@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="account py-120">
        <div class="account-inner">
            <div class="container">
                <div class="d-flex justify-content-center">
                    <div class="verification-code-wrapper account-form">
                        <div class="verification-area">
                            <h4 class="pb-3  border-bottom">@lang('Verify Email Address')</h4>
                            <form class="submit-form" action="{{ route('user.verify.email') }}" method="POST">
                                @csrf
                                <p class="verification-text mb-3">@lang('A 6 digit verification code sent to your email address'): {{ showEmailAddress(auth()->user()->email) }}</p>

                                @include($activeTemplate . 'partials.verification_code')

                                <div class="mb-3">
                                    <button class="btn btn--base w-100" type="submit">@lang('Submit')</button>
                                </div>

                                <div class="mb-3">
                                    <p>
                                        @lang('If you don\'t get any code'), <a class="text--base" href="{{ route('user.send.verify.code', 'email') }}"> @lang('Try again')</a>
                                    </p>

                                    @if ($errors->has('resend'))
                                        <small class="text--danger d-block">{{ $errors->first('resend') }}</small>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/jquery.validate.js') }}"></script>
@endpush
