@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @php
        $contactUsContent = getContent('contact_us.content', true);
    @endphp
    <section class="account py-60 section-gap-heading">
        <div class="account-inner">
            <div class="container">
                <div class="row gy-4 flex-wrap-reverse">
                    <div class="col-lg-6 d-lg-block d-none">
                        <div class="account-thumb">
                            <img src="{{ getImage('assets/images/frontend/contact_us/' . @$contactUsContent->data_values->image, '575x330') }}" alt="@lang('Contact')">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="account-form">
                            <div class="account-form__content mb-4">
                                <h3 class="account-form__title mb-2">{{ __($contactUsContent->data_values->heading) }}</h3>
                                <p class="account-form__desc">{{ __($contactUsContent->data_values->subheading) }}</p>
                            </div>
                            <form class="verify-gcaptcha" action="{{ route('contact') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <div class="form--group">
                                            <label class="form--label" for="name">@lang('Your Name')</label>
                                            <input class="form--control" id="name" name="name" type="text" value="{{ old('name', @$user->fullname) }}" @if ($user && $user->profile_complete) readonly @endif required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <div class="form--group">
                                            <label class="form--label" for="email">@lang('Your Email')</label>
                                            <input class="form--control" id="email" name="email" type="email" value="{{ old('email', @$user->email) }}" @if ($user) readonly @endif required>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 form-group">
                                        <div class="form--group">
                                            <label class="form--label" for="subject">@lang('Your Subject')</label>
                                            <input class="form--control" id="subject" name="subject" type="text" value="{{ old('subject') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 form-group">
                                        <div class="form--group">
                                            <label class="form--label" for="textarea">@lang('Your Message')</label>
                                            <textarea class="form--control" id="textarea" name="message" required>{{ old('message') }}</textarea>
                                        </div>
                                    </div>

                                    <x-captcha />

                                    <div class="col-sm-12 form-group">
                                        <button class="btn btn--base w-100" type="submit">@lang('Send Message')</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif

@endsection
