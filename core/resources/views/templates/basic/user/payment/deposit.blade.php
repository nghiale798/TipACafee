@extends($activeTemplate . 'layouts.' . $layout)
@section('content')
    @auth
        <div class="d-flex gap-2 flex-wrap justify-content-between align-items-center mb-4">
            <h2 class="page-title mb-0">{{ __($pageTitle) }}</h2>
            @if (!request()->routeIs('payment.capture'))
                <a class="btn btn--base btn--sm" href="{{ route('user.deposit.history') }}">
                    <span class="icon"><i class="fa fa-hand-holding-usd"></i></span>
                    @lang('Deposit History')
                </a>
            @endif
        </div>
    @endauth

    <div class="row justify-content-center">
        <div class="col-lg-{{ $layout == 'frontend' ? '5' : '12' }}">
            <div class="multi-page-card">
                <div class="multi-page-card__body">
                    <form action="{{ route('payment.insert') }}" method="post">
                        @csrf
                        @if (auth()->check() && auth()->user()->balance > 0 && request()->routeIs('payment.capture'))
                            <input name="currency" type="hidden" value="{{ $general->cur_text }}">
                        @else
                            <input name="currency" type="hidden">
                        @endif
                        <input name="user_id" type="hidden" value="{{ @$user->id ?? auth()->id() }}">
                        @if (request()->routeIs('payment.capture'))
                            <input name="is_donate" type="hidden" value="1">
                        @endif

                        <div class="form-group">
                            <label class="form--label">@lang('Select Gateway')</label>
                            <select class="gateway-select-box" name="gateway" required>
                                <option data-title="@lang('Select One')" data-charge="@lang('N/A')" value="">
                                    @lang('Select One')</option>
                                @if (auth()->check() && auth()->user()->balance > 0 && request()->routeIs('payment.capture'))
                                    <option data-title="@lang('Deposit Wallet') {{ $general->cur_sym . showAmount(auth()->user()->balance) }} {{ __($general->cur_text) }}" data-charge="@lang('N/A')" value="deposit_wallet">
                                        @lang('From Wallet')</option>
                                @endif

                                @foreach ($gatewayCurrency as $data)
                                    <option data-gateway="{{ $data }}" data-title="{{ __($data->name) }} ({{ gs('cur_sym') }}{{ showAmount($data->min_amount) }} to {{ gs('cur_sym') }}{{ showAmount($data->max_amount) }})" data-charge="{{ gs('cur_sym') }}{{ showAmount($data->fixed_charge) }} + {{ showAmount($data->percent_charge) }}%" value="{{ $data->method_code }}" @selected(old('gateway') == $data->method_code)>
                                        {{ __($data->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form--label">@lang('Amount')</label>
                            <div class="input-group">
                                <input class="form-control form--control" name="amount" type="number" value="{{ old('amount', @$amount) }}" step="any" @if (@$amount) readonly @endif autocomplete="off" required>
                                <span class="input-group-text">{{ __($general->cur_text) }}</span>
                            </div>
                        </div>
                        <div class="mt-3 preview-details d-none">
                            <ul class="list-group list-group-flush">

                                <li class="list-group-item d-flex justify-content-between">
                                    <span>@lang('Charge')</span>
                                    <span><span class="charge fw-bold">0</span> {{ __($general->cur_text) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>@lang('Payable')</span> <span><span class="payable fw-bold"> 0</span>
                                        {{ __($general->cur_text) }}</span>
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
                <div class="selected-gateway d-flex justify-content-between align-items-center">
                    <p class="gateway-title">PayPal - USD ($100 to $15,000)</p>
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
                    $(document).find('.icon-area i').addClass("la-angle-down");
                    whichHide.addClass("d-none");
                    $('.selected-gateway').removeClass('focus');
                }
            });
        }
        selectPostType(
            $('.selected-gateway'),
            $(".gateway-list")
        );

        (function($) {
            "use strict";
            $('select[name=gateway]').on('change', function() {
                if (!$('select[name=gateway]').val()) {
                    $('.preview-details').addClass('d-none');
                    return false;
                }
                var resource = $('select[name=gateway] option:selected').data('gateway');
                var fixed_charge = parseFloat(resource.fixed_charge);
                var percent_charge = parseFloat(resource.percent_charge);
                var rate = parseFloat(resource.rate)
                if (resource.method.crypto == 1) {
                    var toFixedDigit = 8;
                    $('.crypto_currency').removeClass('d-none');
                } else {
                    var toFixedDigit = 2;
                    $('.crypto_currency').addClass('d-none');
                }
                $('.min').text(parseFloat(resource.min_amount).toFixed(2));
                $('.max').text(parseFloat(resource.max_amount).toFixed(2));
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
                var payable = parseFloat((parseFloat(amount) + parseFloat(charge))).toFixed(2);
                $('.payable').text(payable);
                var final_amount = (parseFloat((parseFloat(amount) + parseFloat(charge))) * rate).toFixed(
                    toFixedDigit);
                $('.final_amount').text(final_amount);
                if (resource.currency != '{{ $general->cur_text }}') {
                    var rateElement =
                        `<span class="fw-bold">@lang('Conversion Rate')</span> <span><span  class="fw-bold">1 {{ __($general->cur_text) }} = <span class="rate">${rate}</span>  <span class="method_currency">${resource.currency}</span></span></span>`;
                    $('.rate-element').html(rateElement)
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
                $('.method_currency').text(resource.currency);
                $('input[name=currency]').val(resource.currency);
                $('input[name=amount]').on('input');
            });
            $('input[name=amount]').on('input', function() {
                $('select[name=gateway]').change();
                $('.amount').text(parseFloat($(this).val()).toFixed(2));
            });
        })(jQuery);
    </script>
@endpush
