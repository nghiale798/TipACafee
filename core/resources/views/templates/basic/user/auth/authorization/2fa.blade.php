@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="account py-120">
        <div class="account-inner">
            <div class="container">
                <div class="d-flex justify-content-center">
                    <div class="verification-code-wrapper account-form">
                        <div class="verification-area">
                            <h4 class="pb-3  border-bottom">@lang('2FA Verification')</h4>
                            <form class="submit-form" action="{{ route('user.go2fa.verify') }}" method="POST">
                                @csrf

                                @include($activeTemplate . 'partials.verification_code')

                                <div class="form--group">
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
