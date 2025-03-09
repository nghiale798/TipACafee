@php
    $kycInstruction = getContent('kyc_instruction.content', true);
@endphp
@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container">
        @if ($user->kv == Status::KYC_UNVERIFIED)
            <div class="multi-page-card mb-4">
                <div class="multi-page-card__body">
                    <h4 class="text--danger mb-3">@lang('KYC Unverified')</h4>
                    <p>{{ __(@$kycInstruction->data_values->verification_instruction) }}
                        <a class="text--danger" href="{{ route('user.kyc.form') }}">@lang('Verify Now')</a>
                    </p>
                </div>
            </div>
        @elseif($user->kv == Status::KYC_PENDING)
            <div class="multi-page-card mb-4">
                <div class="multi-page-card__body">
                    <h4 class="text--warning mb-3">@lang('KYC Pending')</h4>
                    <p>{{ __(@$kycInstruction->data_values->pending_instruction) }}
                        <a class="text--warning" href="{{ route('user.kyc.data') }}">@lang('See KYC Data')</a>
                    </p>
                </div>
            </div>
        @endif
        <div class="multi-page-card mb-4">
            <div class="multi-page-card__body">
                <div class="flex-between dashboard-profile-content pb-3">
                    <div class="profile-info flex-between">
                        <div class="dashboard-profile_thumb">
                            <img src="{{ avatar(@$user->image ? getFilePath('userProfile') . '/' . $user->image : null) }}" alt="profile">
                        </div>
                        <div class="dashboard-profile_info">
                            <h4 class="dashboard-profile_title mb-0">@lang('Hi'), {{ __($user->firstname) }}</h4>
                            <a class="dashboard-profile_desc" href="{{ route('home') }}/{{ $user->profile_link }}">{{ route('home') }}/{{ $user->profile_link }}</a>
                        </div>
                    </div>
                    <div class="flex-wrap d-flex gap-2">
                        <div class="shared-btn">
                            <button class="btn btn--outline btn--sm pageShareBtn" type="button">
                                <span class="icon-alt shared-btn__icon">
                                    <img src="{{ getImage($activeTemplateTrue . 'images/icons/share.png') }}" alt="">
                                </span>
                                <span class="create-page-text">@lang('Share Page')</span>
                            </button>
                        </div>
                        <div class="earning-dropdown dropdown">
                            <div class="dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="language-text">@lang('All Times')</span>
                            </div>
                            <ul class="dropdown-menu">
                                <li class="earning" data-earning="30">@lang('Last 30 Days')</li>
                                <li class="earning" data-earning="90">@lang('Last 90 Days')</li>
                                <li class="earning" data-earning="1">@lang('All Times')</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="earning-content">
                    <div class="earning-card_list">
                        <div class="earning-card_item">
                            <h5 class="earning-card__title">{{ $general->cur_sym }}<span>{{ showAmount($user->balance) }} </span>
                            </h5>
                            <span class="earning-card__desc">@lang('Total Balance')</span>
                            <span class="earning-card__icon">
                                <img src="{{ getImage($activeTemplateTrue . 'images/icons/money.png') }}" alt="@lang('Balance')">
                            </span>
                        </div>
                        <div class="earning-card_item">
                            <h5 class="earning-card__title">{{ $general->cur_sym }}<span class="donationsEarning">{{ showAmount($donations) }}</span>
                            </h5>
                            <span class="earning-card__desc">@lang('Supporters')</span>
                            <span class="earning-card__icon"><img src="{{ getImage($activeTemplateTrue . 'images/icons/handshake.png') }}" alt="@lang('handshake')"></span>
                        </div>
                        <div class="earning-card_item">
                            <h5 class="earning-card__title">{{ $general->cur_sym }}<span class="membershipEarning">{{ showAmount($membership) }}</span>
                            </h5>
                            <span class="earning-card__desc">@lang('Membership')</span>
                            <span class="earning-card__icon"><img src="{{ getImage($activeTemplateTrue . 'images/icons/membership-dash.png') }}" alt="@lang('handshake')"></span>
                        </div>
                        <div class="earning-card_item">
                            <h5 class="earning-card__title">{{ $general->cur_sym }}<span class="totalEarning">{{ showAmount($totalDonation) }}</span>
                            </h5>
                            <span class="earning-card__desc">@lang('Total Earnings')</span>
                            <span class="earning-card__icon">
                                <img src="{{ getImage($activeTemplateTrue . 'images/icons/money.png') }}" alt="@lang('handshake')">
                            </span>
                        </div>



                        <div class="earning-card_item">
                            <h5 class="earning-card__title">{{ $general->cur_sym }}<span class="totalUGoal">{{ showAmount($totalGoalAchieve) }} </span> </h5>
                            <span class="earning-card__desc">@lang('Total Achieve Goal')</span>
                            <span class="earning-card__icon">
                                <img src="{{ getImage($activeTemplateTrue . 'images/icons/goal.png') }}" alt="@lang('Goal')">
                            </span>
                        </div>
                        <div class="earning-card_item">
                            <h5 class="earning-card__title"><span class="totalUTrx">{{ $totalTransactions }} </span>
                            </h5>
                            <span class="earning-card__desc">@lang('Total Transactions')</span>
                            <span class="earning-card__icon">
                                <img src="{{ getImage($activeTemplateTrue . 'images/icons/transaction.png') }}" alt="@lang('trx')">
                            </span>
                        </div>


                    </div>
                </div>

            </div>
        </div>

        <div class="multi-page-card mb-4">
            <div class="multi-page-card__body">
                <h4>@lang('Recent Supporters')</h4>
                <div class="accordion donation--accordion" id="transactionAccordion">
                    @if (blank($recentSupports))
                        @include($activeTemplate . 'partials.empty', ['message' => 'No recent supporters found!'])
                    @else
                        @foreach ($recentSupports as $support)
                            @php
                                $avatar = '';
                                $profile = '';
                                $profileUser = '';
                                if (@$support->supporter) {
                                    $avatar = $support->supporter->image;
                                    $profile = $support->supporter->profile_link;
                                    $profileUser = $support->supporter;
                                } else {
                                    $avatar = @$support->member->image;
                                    $profile = @$support->member->profile_link;
                                    $profileUser = @$support->member;
                                }
                            @endphp
                            <div class="accordion-item border-0">
                                <div class="accordion-header" id="h-{{ $loop->index + 1 }}">
                                    <div class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#c-{{ $loop->index + 1 }}" role="button" aria-expanded="false" aria-controls="c-1">
                                        <div class="col-sm-6 col-12">
                                            <div class="left flex-align">
                                                <div class="icon base">
                                                    <img src="{{ avatar($avatar ? getFilePath('userProfile') . '/' . $avatar : null) }}" alt="">
                                                </div>
                                                <div class="content">
                                                    <h6 class="title mb-0">
                                                        {{ @$profile ?? __('Anonymous Support') }}
                                                    </h6>
                                                    <span class="mail">
                                                        @if (@$profile)
                                                            {{ $profileUser->email }}
                                                        @else
                                                            @lang('Anonymous Support')
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3 text-end">
                                            <p class="donation-user">{{ $general->cur_sym }}{{ showAmount($support->amount) }}</p>
                                        </div>
                                        <div class="col-3 text-end ">
                                            <p class="donation-time"> {{ showDateTime($support->created_at) }}</p>
                                            <small class="donation-time fs-14"> {{ diffForHumans($support->created_at) }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-collapse collapse" id="c-{{ $loop->index + 1 }}" data-bs-parent="#transactionAccordion" aria-labelledby="h-{{ $loop->index + 1 }}" style="">
                                    <div class="accordion-body">
                                        <ul class="caption-list">
                                            <li class="caption-list__item">
                                                <span class="caption">@lang('Message')</span>
                                                <span class="value">{{ $support->message }}</span>
                                            </li>
                                            <li class="caption-list__item">
                                                <span class="caption">@lang('Payment Method')</span>
                                                <span class="value">{{ __(@$support->deposit->gateway->name) }}</span>
                                            </li>
                                            @if ($support->membership)
                                                <li class="caption-list__item">
                                                    <span class="caption">@lang('MemberShip Level')</span>
                                                    <span class="value">{{ __(@$support->membership->level->name) }}</span>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div class="multi-page-card">
            <div class="multi-page-card__body">
                <div class="row g-2 align-items-center justify-content-between">
                    <div class="col-sm-6">
                        <div class="d-flex">
                            <h5>@lang('Total Amount')</h5>
                            <span class="text--base total_amount fw-bold "> </span>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <select class="widget_select form--control form--select" name="donation_time">
                            <option value="week">@lang('Current week')</option>
                            <option value="month" selected>@lang('Current month')</option>
                            <option value="year">@lang('Current year')</option>
                        </select>
                    </div>
                </div>
                <div class="card-body text-center">
                    <div id="chart-area"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/global/js/vendor/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/vendor/chart.js.2.8.0.js') }}"></script>
    <script>
        "use strict";

        $('.earning-dropdown .dropdown-menu li').on('click', function() {
            var dataEarning = $(this).data('earning');
            var textMap = {
                '30': '@lang('Last 30 Days')',
                '90': '@lang('Last 90 Days')',
                '1': '@lang('All Times')'
            };
            $('.dropdown-toggle .language-text').text(textMap[dataEarning]);

            var action = '{{ route('user.get.earnings') }}';
            var earningData = {
                'earning_for': dataEarning
            };

            $.ajax({
                url: action,
                method: 'GET',
                data: earningData,
                success: function(response) {
                    if (response.success) {
                        $('.totalEarning').text(Number(response.data.total).toFixed(2));
                        $('.donationsEarning').text(Number(response.data.donations).toFixed(2));
                        $('.membershipEarning').text(Number(response.data.membership).toFixed(2));
                        $('.totalUGoal').text(Number(response.data.total_goal).toFixed(2));
                        $('.totalUTrx').text(Number(response.data.total_trx));
                    }
                },
                error: function(xhr, status, error) {
                    notify('error', error);
                }
            });
        });


        //apex-chat
        var chart;

        $('[name=donation_time]').on('change', function() {
            let time = $(this).val();
            let url = "{{ route('user.get.statistics') }}";

            $.get(url, {
                time: time,
            }, function(response) {
                if (!response.chart_data) {
                    return;
                }

                let successData = [];
                let labels = [];

                $.each(response.chart_data, function(label, value) {
                    labels.push(label);
                    successData.push(value.success);
                });

                $('.total_amount').text(`{{ $general->cur_sym }}` + response.total_amount);

                var options = {
                    series: [{
                        name: "Donation",
                        data: successData
                    }],
                    chart: {
                        type: 'area',
                        height: 350,
                        zoom: {
                            enabled: false
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'straight'
                    },
                    labels: labels,
                    yaxis: {
                        opposite: true
                    },
                    xaxis: {
                        categories: labels,
                    },
                    legend: {
                        horizontalAlign: 'left'
                    }
                };

                if (chart) {
                    chart.destroy();
                }

                chart = new ApexCharts(document.querySelector("#chart-area"), options);
                chart.render();

            });
        }).change();
    </script>
@endpush
