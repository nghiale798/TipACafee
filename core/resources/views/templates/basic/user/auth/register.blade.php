@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @php
        $register = getContent('register.content', true);
        $policyPages = getContent('policy_pages.element', false, null, true);
        if (session()->has('username')) {
            $username = session()->get('username');
        }
    @endphp

    <section class="account py-60 section-gap-heading">
        <div class="account-inner">
            <div class="container">
                <div class="row gy-4 flex-wrap-reverse">
                    <div class="col-lg-6  d-lg-block d-none">
                        <div class="account-thumb">
                            <img src="{{ getImage('assets/images/frontend/register/' . @$register->data_values->image, '636x655') }}" alt="">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="account-form">
                            <div class="account-form__content mb-4">
                                <h4 class="account-form__title mb-2"> {{ __(@$register->data_values->heading) }} </h4>
                            </div>
                            <form class="verify-gcaptcha" action="{{ route('user.register') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <div class="form--group">
                                            <label class="form--label" for="name"> @lang('Username')</label>
                                            <input class="form--control checkUser" id="name" name="username" type="text" value="{{ old('username', @$username) }}" required>
                                            <small class="text--danger usernameExist"></small>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <div class="form--group">
                                            <label class="form--label" for="email">@lang('Email')</label>
                                            <input class="form--control checkUser" id="email" name="email" type="text" value="{{ old('email') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label class="form--label" for="country-name">@lang('Country')</label>
                                        <select class="select form-select" id="country-name" name="country">
                                            @foreach ($countries as $key => $country)
                                                <option data-mobile_code="{{ $country->dial_code }}" data-code="{{ $key }}" value="{{ $country->country }}">
                                                    {{ __($country->country) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label class="form--label" for="your-password">@lang('Mobile')</label>
                                        <input name="mobile_code" type="hidden">
                                        <input name="country_code" type="hidden">
                                        <div class="input-group input-with-text">
                                            <span class="input-group-text fixed-number mobile-code"></span>
                                            <input class="form-control form--control checkUser" name="mobile" type="number" value="{{ old('mobile') }}" id="mobile-number" required>
                                        </div>
                                        <small class="text--danger mobileExist"></small>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label class="form--label" for="your-password">@lang('Password')</label>
                                        <div class="position-relative">
                                            <input class="form--control @if ($general->secure_password) secure-password @endif" id="your-password" name="password" type="password" required>
                                            <span class="password-show-hide fas fa-eye toggle-password fa-eye-slash" id="#your-password"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label class="form--label" for="confirm-password">@lang('Confirm Password')</label>
                                        <div class="position-relative">
                                            <input class="form--control" id="confirm-password" name="password_confirmation" type="password" required>
                                            <div class="password-show-hide fas fa-eye toggle-password fa-eye-slash" id="#confirm-password"></div>
                                        </div>
                                    </div>

                                    <x-captcha />
                                    
                                    @if($general->agree)
                                    <div class="form-group  col-sm-12">
                                        <input type="checkbox" id="agree" @checked(old('agree')) name="agree" required>
                                        <label for="agree">@lang('I agree with')</label> <span>@foreach($policyPages as $policy) <a class="text--base" href="{{ route('policy.pages',[slug($policy->data_values->title),$policy->id]) }}" target="_blank">{{ __($policy->data_values->title) }}</a> @if(!$loop->last), @endif @endforeach</span>
                                    </div>
                                    @endif
                                    <div class="col-sm-12 form-group">
                                        <button class="btn btn--base w-100" id="recaptcha" type="submit">@lang('Sign Up')</button>
                                    </div>
                                    @include($activeTemplate . 'user.auth.social_login')
                                    <div class="col-sm-12 form-group">
                                        <div class="have-account">
                                            <p class="have-account__text">@lang('Already have an account?') <a class="have-account__link text--base" href="{{ route('user.login') }}">@lang('Sign in')</a> @lang('now')</p>
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

    <div class="modal fade confirm-modal custom--modal" id="existModalCenter" aria-hidden="true" tabindex="-1" style="display: none;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h4 class="modal-title mb-3">@lang('You are with us')</h4>
                    <p class="modal-desc mb-3">
                        @lang('You already have an account please sign in')
                    </p>
                    <a class="btn btn--base w-100 mb-3" href="{{ route('user.login') }}">@lang('Sign in')</a>
                    <button class="btn btn-outline--light w-100 mt-3" data-bs-dismiss="modal">@lang('Cancel')</button>
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
@push('script')
    <script>
        "use strict";
        (function($) {
            @if ($mobileCode)
                $(`option[data-code={{ $mobileCode }}]`).attr('selected', '');
            @endif

            $('select[name=country]').on('change', function() {
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
            });
            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));

            $('.checkUser').on('focusout', function(e) {
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';
                if ($(this).attr('name') == 'mobile') {
                    var mobile = `${$('.mobile-code').text().substr(1)}${value}`;
                    var data = {
                        mobile: mobile,
                        _token: token
                    }
                }
                if ($(this).attr('name') == 'email') {
                    var data = {
                        email: value,
                        _token: token
                    }
                }
                if ($(this).attr('name') == 'username') {
                    var data = {
                        username: value,
                        _token: token
                    }
                }
                $.post(url, data, function(response) {
                    if (response.data != false && response.type == 'email') {
                        $('#existModalCenter').modal('show');
                    } else if (response.data != false) {
                        $(`.${response.type}Exist`).text(`${response.type} already exist`);
                    } else {
                        $(`.${response.type}Exist`).text('');
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
