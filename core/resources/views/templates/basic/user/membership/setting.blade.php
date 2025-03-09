@extends($activeTemplate . 'layouts.master')
@section('content')
    @php
        $levelContent = getContent('membership_level.content', true);
        $welcomeMessage = $setting->welcome_message ?? $general->thank_you_message;
    @endphp

    <div class="multi-page-card">
        @include($activeTemplate . 'user.membership.navbar')
        <form action="{{ route('user.membership.setting.update', $setting->id) }}" method="POST">
            @csrf
            <div class="multi-page-card__body">
                <div class="setting-list">
                    <div class="setting-list__header flex-between">
                        <h5 class="setting-list__title">@lang('Accept Annual Membership')</h5>
                        <div class="setting-list__check">
                            <label class="form--switch">
                                <input name="accept_annual_membership" type="checkbox" @if ($setting->accept_annual_membership) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <p class="setting-list__desc">
                        @lang('New members are offered the choice to prepay for a full year of membership.')
                    </p>
                </div>
                <div class="setting-list">
                    <div class="setting-list__header flex-between">
                        <h5 class="setting-list__title">@lang('Display Member Count')</h5>
                        <div class="setting-list__check">
                            <label class="form--switch">
                                <input name="is_show_count" type="checkbox" @if ($setting->is_show_count) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <p class="setting-list__desc">
                        @lang('Showing your member count might encourage more people to join.')
                    </p>
                </div>
                <div class="setting-list">
                    <div class="setting-list__header flex-between">
                        <h5 class="setting-list__title">@lang('Display Monthly Earnings')</h5>
                        <div class="setting-list__check">
                            <label class="form--switch">
                                <input name="is_show_earning" type="checkbox" @if ($setting->is_show_earning) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <p class="setting-list__desc">
                        @lang('Displaying earnings allows you to be transparent with your supporters.')
                    </p>
                </div>
                <div class="thankyou-message border-0">
                    <div class="thankyou-message__header flex-between">
                        <h5 class="thankyou-message__title card-title">@lang('Welcome Message')</h5>
                        <button class="btn--outline btn--sm preview-btn flex-align welcomeYouModal" type="button"> <i class="fa fa-eye"></i>
                            @lang('Preview')</button>
                    </div>
                    <div class="thankyou-message__body">
                        <p class="thankyou-message__desc">@lang('This will be visible after the 1st membership payment and in the receipt email. Write a personable welcome message, and include any rewards if you like').</p>
                        <div class="thankyou-message__input">
                            <textarea class="form--control" name="welcome_message">{{ old('welcome_message', @$welcomeMessage) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column flex-wrap gap-3">
                    <button class="btn btn--base  w-100">@lang('Save Changes')</button>
                    <p class="text-center">
                        <span class="text-decoration-underline cursor-pointer confirmationAltBtn" data-question="@lang('Are you sure to deactivate membership?')"
                            data-action="{{ route('user.membership.status') }}">@lang('Deactivate Membership')</span>
                    </p>
                </div>
            </div>
        </form>
    </div>

    <x-confirmation-alert />

    @include($activeTemplate . 'profile_page.membership.welcome_modal')
@endsection

@push('script')
    <script>
        'use strict';
        $(document).ready(function() {
            $('.welcomeYouModal').on('click', function() {
                $('#welcomeModal').modal('show');
            })
        });
    </script>
@endpush
