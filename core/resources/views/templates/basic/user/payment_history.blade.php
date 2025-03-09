@extends($activeTemplate . 'layouts.master')
@section('content')
    <h2 class="page-title">{{ __($pageTitle) }}</h2>
    <div class="multi-page-card">
        <div class="multi-page-card__body">
            @if ($countPayment)
                <div class="donation-overview-card-list justify-content-center">
                    <div class="donation-overview-card">
                        <h5 class="donation-overview-card__title"> {{ $countPayment }}</h5>
                        <div class="donation-overview-card__content flex-wrap">
                            <span class="icon"><i class="las la-money-bill-wave-alt"></i></span>
                            @lang('Total Payments')
                        </div>
                    </div>
                    <div class="donation-overview-card">
                        <h5 class="donation-overview-card__title">
                            <span class="icon">{{ $general->cur_sym }}</span>{{ showAmount($lastThirtyDaysPayment) }}
                        </h5>
                        <div class="donation-overview-card__content flex-wrap">
                            <span class="icon"><i class="far fa-calendar-alt"></i></span>
                            @lang('Last 30 Days')
                        </div>
                    </div>
                    <div class="donation-overview-card">
                        <h5 class="donation-overview-card__title">
                            <span class="icon">{{ $general->cur_sym }}</span>{{ showAmount($totalPayment) }}
                        </h5>
                        <div class="donation-overview-card__content flex-wrap">
                            <span class="icon"><i class="las la-hand-holding-usd"></i></span>
                            @lang('All - Time')
                        </div>
                    </div>
                </div>
                <table class="table pt-44 table table--responsive--sm">
                    <thead>
                        <tr>
                            <th>@lang('Payment Date')</th>
                            <th>@lang('User')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Payment as')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Message')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            <tr>
                                <td>{{ showdateTime($payment->create_at) }}</td>
                                <td>
                                    {{ $payment->user->fullname }}
                                </td>
                                <td>{{ $general->cur_sym }}{{ showAmount($payment->amount) }} </td>
                                <td>
                                    @if ($payment->supporter_id)
                                        @lang('Supporter')
                                    @else
                                        @lang('Member')
                                    @endif
                                </td>
                                <td>
                                    @if ($payment?->deposit)
                                        @php
                                            echo $payment?->deposit?->statusBadge;
                                        @endphp
                                    @else
                                        <span class="badge badge--success">@lang('Succeed')</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($payment->message)
                                        {{ __($payment->message) }}
                                    @else
                                        @lang('N/A')
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if ($payments->hasPages())
                    {{ paginateLinks($payments) }}
                @endif
            @else
                @include($activeTemplate . 'partials.empty', ['message' => 'No payment found!'])
            @endif
        </div>
    </div>
@endsection
