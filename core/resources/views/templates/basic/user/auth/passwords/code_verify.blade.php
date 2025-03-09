@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="account py-120">
        <div class="account-inner">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-7 col-xl-5">
                        <div class="d-flex justify-content-center">
                            <div class="verification-code-wrapper account-form">
                                <div class="verification-area">
                                    <h4 class="pb-3  border-bottom">@lang('Verify Email Address')</h4>
                                    <form class="submit-form" action="{{ route('user.password.verify.code') }}" method="POST">
                                        @csrf
                                        <p class="verification-text pb-3">@lang('A 6 digit verification code sent to your email address') : {{ showEmailAddress($email) }}</p>
                                        <input name="email" type="hidden" value="{{ $email }}">

                                        @include($activeTemplate . 'partials.verification_code')

                                        <div class="form-group">
                                            <button class="btn btn--base w-100" type="submit">@lang('Submit')</button>
                                        </div>

                                        <div class="form-group">
                                            @lang('Please check including your Junk/Spam Folder. if not found, you can')
                                            <a class="text--base" href="{{ route('user.password.request') }}">@lang('Try to send again')</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
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
