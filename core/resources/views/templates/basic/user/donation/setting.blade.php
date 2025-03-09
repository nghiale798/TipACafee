@php
    $coffee = @$donation->donation_price ?? $general->starting_price;
    $isCause = @$donation->cause_percent && @$donation->institute;
    $message = $donation->thanks_message ?? $general->thank_you_message;
@endphp

@extends($activeTemplate . 'layouts.master')
@section('content')
<h2 class="page-title">{{ __($pageTitle) }}</h2>
    <div class="multi-page-card">
        @include($activeTemplate . 'user.donation.navbar')
        <div class="multi-page-card__body">
            <form action="{{ route('user.donation.setting.store') }}" method="POST">
                @csrf
                <div class="coffee-price">
                    <h5 class="coffee-price__title card-title">@lang('Price Per') {{ __(keyToTitle($user->donate_emoji_name)) }}</h5>
                    <div class="coffee-price__content">
                        <p class="coffee-price__desc">@lang('Change the default price of a') {{ __(strtolower($user->donate_emoji_name)) }} @lang('to an amount of your choice.')</p>
                        <input name="donation_price" type="hidden" value="{{ $coffee }}" />
                        <div class="coffee-price__price-list flex-wrap">
                            <span class="price-item flex-center @if (1 == $coffee) active @endif" data-donation_price="1">{{ $general->cur_sym }}1</span>
                            <span class="price-item flex-center @if (2 == $coffee) active @endif" data-donation_price="2">{{ $general->cur_sym }}2</span>
                            <span class="price-item flex-center @if (3 == $coffee) active @endif" data-donation_price="3">{{ $general->cur_sym }}3</span>
                            <span class="price-item flex-center @if (4 == $coffee) active @endif" data-donation_price="4">{{ $general->cur_sym }}4</span>
                            <span class="price-item flex-center @if (5 == $coffee) active @endif" data-donation_price="5">{{ $general->cur_sym }}5</span>
                            <span class="price-item flex-center @if (10 == $coffee) active @endif" data-donation_price="10">{{ $general->cur_sym }}10</span>
                            <span class="price-item flex-center @if (15 == $coffee) active @endif" data-donation_price="15">{{ $general->cur_sym }}15</span>
                        </div>
                    </div>
                </div>
                <div class="thankyou-message">
                    <div class="thankyou-message__header flex-between">
                        <h5 class="thankyou-message__title card-title">@lang('Thank You Message')</h5>
                        <button class="btn--outline btn--sm preview-btn flex-align" data-bs-toggle="modal" data-bs-target="#thankYouModal" type="button"> <i class="fa fa-eye"></i>
                            @lang('Preview')</button>

                    </div>
                    <div class="thankyou-message__body">
                        <p class="thankyou-message__desc">@lang('This will be visible after the payment and in the receipt email. Write a personable thank you message, and include any rewards if you like').</p>
                        <div class="thankyou-message__input">
                            <textarea class="form--control" name="thanks_message">{{ old('thanks_message', @$message) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="indicate-donation ">
                    <div class="indicate-donation__header flex-wrap">
                        <h5 class="thankyou-message-card__title card-title">@lang('Indicate My Donation')</h5>
                        <label class="form--switch">
                            <input name="is_donatio_cause" type="checkbox" @if (request()->is_donatio_cause || @$isCause) checked @endif>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div class="indicate-donation__body d-none">
                        <p class="indicate-donation__desc">@lang('If you’re donating your earnings to a cause or a charity, you can display a message on your page.')</p>

                        <div class="indicate-donation__calculation">
                            <div class="input-group custom-input-group mb-3">
                                <input class="form--control form-control" name="cause_percent" type="number" value="{{ old('cause_percent', @$donation->cause_percent) }}" placeholder="99" min="0" max="100" step="any" />
                                <span class="input-group-text">%</span>
                            </div>
                            <p class="indicate-donation__calculation-desc">@lang('of all proceeds go to')</p>
                            <input class="form--control input-level" name="institute" type="text" value="{{ old('institute', @$donation->institute) }}" placeholder="Choose Love" />
                        </div>
                    </div>
                </div>

                <div class="donation-change-submit ">
                    <button class="btn--base btn w-100" type="submit">@lang('Save Change')</button>
                </div>
            </form>
        </div>
    </div>

    {{-- tnx -modal --}}
    @include($activeTemplate . 'profile_page.post.thanks_modal')
@endsection

@push('script')
    <script>
        "use strict";
        (function($) {

            $(".coffee-price__price-list span").on('click', function() {
                $(".coffee-price__price-list span").removeClass("active");
                $(this).addClass("active");
                var donationPrice = $(this).data("donation_price");
                $("[name=donation_price]").val(donationPrice);
            });


            if (`{{ $isCause }}`) {
                $('.indicate-donation__body').removeClass('d-none');
            }
            $("[name=is_donatio_cause]").on('change', function() {
                if ($(this).is(':checked')) {
                    $('.indicate-donation__body').removeClass('d-none');
                } else {
                    $('.indicate-donation__body').addClass('d-none');
                }
            });

            //copy-for-share//
            $('.copy-btn').on('click', async function() {
                var link = $(this).data('link');
                await navigator.clipboard.writeText(link);
                this.classList.add('copied');
                setTimeout(() => this.classList.remove('copied'), 2000);
            });

        })(jQuery);
    </script>
@endpush
