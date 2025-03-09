@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="d-flex gap-2 flex-wrap justify-content-between align-items-center mb-4">
        <h2 class="page-title mb-0">{{ __($pageTitle) }}</h2>
        <a class="btn btn--base btn--sm" href="{{ route('user.withdraw.history') }}">
            <span class="icon"><i class="fa fa-hand-holding-usd"></i></span>
            @lang('Payout History')
        </a>
    </div>
    <div class="multi-page-card">
        <div class="multi-page-card__body">
            @php
                $user = auth()->user();
            @endphp
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <form action="{{ route('user.withdraw.money') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">@lang('Select Method')</label>
                            <select class="gateway-select-box" name="method_code" required>
                                <option data-title="@lang('Select One')" data-charge="@lang('N/A')" value="">
                                    @lang('Select One')</option>
                                @foreach ($withdrawMethod as $data)
                                    <option data-gateway="{{ $data }}" data-title="{{ __($data->name) }} ({{ gs('cur_sym') }}{{ showAmount($data->min_limit) }} to {{ gs('cur_sym') }}{{ showAmount($data->max_limit) }})" data-charge="{{ gs('cur_sym') }}{{ showAmount($data->fixed_charge) }} + {{ getAmount($data->percent_charge) }}%" value="{{ $data->id }}" @selected(old('gateway') == $data->method_code)>
                                        {{ __($data->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">@lang('Amount')</label>
                            <div class="input-group">
                                <input class="form-control form--control" name="amount" type="number" value="{{ old('amount') }}" step="any" autocomplete="off" required>
                                <span class="input-group-text">{{ __($general->cur_text) }}</span>
                            </div>
                            <div class="my-2 d-flex justify-content-between">
                                <span>@lang('Available Balance'):</span> <span class="text--base">{{$general->cur_sym . showAmount($user->balance) }} {{ $general->cur_text }}</span>
                            </div>
                        </div>
                        <div class="my-3 preview-details d-none">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>@lang('Limit')</span>
                                    <span><span class="min fw-bold">0</span> {{ __($general->cur_text) }} - <span class="max fw-bold">0</span>
                                        {{ __($general->cur_text) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>@lang('Charge')</span>
                                    <span><span class="charge fw-bold">0</span> {{ __($general->cur_text) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>@lang('Receivable')</span> <span><span class="receivable fw-bold"> 0</span>
                                        {{ __($general->cur_text) }}</span>
                                </li>
                                <li class="list-group-item justify-content-between d-none rate-element">

                                </li>
                                <li class="list-group-item justify-content-between d-none in-site-cur">
                                    <span>@lang('In') <span class="method_currency"></span></span>
                                    <span class="final_amount fw-bold">0</span>
                                </li>
                                <li class="list-group-item justify-content-center crypto_currency d-none">
                                    <span>@lang('Conversion with') <span class="method_currency"></span>
                                        @lang('and final value will Show on next step')</span>
                                </li>
                            </ul>
                        </div>
                        <button class="btn btn--base w-100 mt-3" type="submit">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        var gatewayOptions = $('.gateway-select-box').find('option');

        var gatewayHtml = `
            <div class="gateway-select">
                <div class="selected-gateway d-flex justify-content-between align-items-center form-control">
                    <p class="gateway-title">Bank - USD ($100 to $10,000)</p>
                    <div class="icon-area">
                        <i class="las la-angle-down"></i>
                    </div>
                </div>
                <div class="gateway-list d-none">
        `;
        $.each(gatewayOptions, function(key, option) {
            option = $(option);
            if (option.data('title') && option.data('charge') != 'N/A') {
                gatewayHtml += `<div class="single-gateway" data-value="${option.val()}">
                            <p class="gateway-title">${option.data('title')}</p>
                            <p class="gateway-charge">Charge: ${option.data('charge')}</p>
                        </div>`;
            } else {
                gatewayHtml += `<div class="single-gateway" data-value="${option.val()}">
                            <p class="gateway-title">${option.data('title')}</p>
                        </div>`;
            }
        });
        gatewayHtml += `</div></div>`;
        $('.gateway-select-box').after(gatewayHtml);
        var selectedGateway = $('.gateway-select-box :selected');
        $(document).find('.selected-gateway .gateway-title').text(selectedGateway.data('title'))

        $('.selected-gateway').on('click', function() {
            $('.gateway-list').toggleClass('d-none');
            $(this).toggleClass('focus');
            $(this).find('.icon-area').find('i').toggleClass('la-angle-up');
            $(this).find('.icon-area').find('i').toggleClass('la-angle-down');
        });

        $(document).on('click', '.single-gateway', function() {
            $('.selected-gateway').find('.gateway-title').text($(this).find('.gateway-title').text());
            $('.gateway-list').addClass('d-none');
            $('.selected-gateway').removeClass('focus');
            $('.selected-gateway').find('.icon-area').find('i').toggleClass('la-angle-up');
            $('.selected-gateway').find('.icon-area').find('i').toggleClass('la-angle-down');
            $('.gateway-select-box').val($(this).data('value'));
            $('.gateway-select-box').trigger('change');
        });

        function selectPostType(whereClick, whichHide) {
            if (!whichHide) return;

            $(document).on("click", function(event) {
                var target = $(event.target);
                if (!target.closest(whereClick).length) {
                    $(document).find('.icon-area i').addClass("la-angle-down").removeClass('la-angle-up');
                    whichHide.addClass("d-none");
                    whereClick.removeClass('focus');
                }
            });
        }
        selectPostType(
            $('.selected-gateway'),
            $(".gateway-list")
        );


        (function($) {
            "use strict";
            $('select[name=method_code]').on("change", function() {
                if (!$('select[name=method_code]').val()) {
                    $('.preview-details').addClass('d-none');
                    return false;
                }
                var resource = $('select[name=method_code] option:selected').data('gateway');
                var fixed_charge = parseFloat(resource.fixed_charge);
                var percent_charge = parseFloat(resource.percent_charge);
                var rate = parseFloat(resource.rate)
                var toFixedDigit = 2;
                $('.min').text(parseFloat(resource.min_limit).toFixed(2));
                $('.max').text(parseFloat(resource.max_limit).toFixed(2));
                var amount = parseFloat($('input[name=amount]').val());
                if (!amount) {
                    amount = 0;
                }
                if (amount <= 0) {
                    $('.preview-details').addClass('d-none');
                    return false;
                }

                $('.preview-details').removeClass('d-none');

                var charge = parseFloat(fixed_charge + (amount * percent_charge / 100)).toFixed(2);
                $('.charge').text(charge);
                
                if (resource.currency != '{{ $general->cur_text }}') {
                    var rateElement =
                        `<span>@lang('Conversion Rate')</span> <span class="fw-bold">1 {{ __($general->cur_text) }} = <span class="rate">${rate}</span>  <span class="base-currency">${resource.currency}</span></span>`;
                    $('.rate-element').html(rateElement);
                    $('.rate-element').removeClass('d-none');
                    $('.in-site-cur').removeClass('d-none');
                    $('.rate-element').addClass('d-flex');
                    $('.in-site-cur').addClass('d-flex');
                } else {
                    $('.rate-element').html('')
                    $('.rate-element').addClass('d-none');
                    $('.in-site-cur').addClass('d-none');
                    $('.rate-element').removeClass('d-flex');
                    $('.in-site-cur').removeClass('d-flex');
                }
                var receivable = parseFloat((parseFloat(amount) - parseFloat(charge))).toFixed(2);
                $('.receivable').text(receivable);
                var final_amount = parseFloat(parseFloat(receivable) * rate).toFixed(toFixedDigit);
                $('.final_amount').text(final_amount);
                $('.base-currency').text(resource.currency);
                $('.method_currency').text(resource.currency);
                $('input[name=amount]').on('input');
            });
            $('input[name=amount]').on('input', function() {
                var data = $('select[name=method_code]').change();
                $('.amount').text(parseFloat($(this).val()).toFixed(2));
            });
        })(jQuery);
    </script>
@endpush
