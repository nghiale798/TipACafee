@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="multi-page-card">
        <div class="multi-page-card__body">
            @if ($countWithdraw)
                <div class="donation-overview-card-list justify-content-center">
                    <div class="donation-overview-card">
                        <h5 class="donation-overview-card__title"> {{ $countWithdraw }}</h5>
                        <div class="donation-overview-card__content flex-wrap">
                            <span class="icon"><i class="las la-money-bill-wave-alt"></i></span>
                            {{ __(str()->plural('Total Payout', $countWithdraw)) }}
                        </div>
                    </div>
                    <div class="donation-overview-card">
                        <h5 class="donation-overview-card__title">
                            <span class="icon">{{ $general->cur_sym }}</span>{{ showAmount($lastThirtyDaysWithdraw) }}
                        </h5>
                        <div class="donation-overview-card__content flex-wrap">
                            <span class="icon"><i class="far fa-calendar-alt"></i></span>
                            @lang('Last 30 Days')
                        </div>
                    </div>
                    <div class="donation-overview-card">
                        <h5 class="donation-overview-card__title">
                            <span class="icon">{{ $general->cur_sym }}</span>{{ showAmount($totalWithdraw) }}
                        </h5>
                        <div class="donation-overview-card__content flex-wrap">
                            <span class="icon"><i class="las la-hand-holding-usd"></i></span>
                            @lang('All - Time')
                        </div>
                    </div>
                </div>
                <!-- Payments List Table Start -->
                <div class="verticalResponsiveTableMdLg">
                    <table class="table py-44 table table--responsive--md">
                        <thead>
                            <tr>
                                <th>@lang('Gateway | Trx.')</th>
                                <th>@lang('Initiated')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Conversion')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($withdraws as $withdraw)
                                <tr>
                                    <td>
                                        <div class="td-wrapper">
                                            <span class="fw-bold"><span class="text-primary"> {{ __(@$withdraw->method->name) }}</span></span>
                                            <br>
                                            <small>{{ $withdraw->trx }}</small>
                                        </div>
                                    </td>
                                    <td class="text-end text-md-center">
                                        {{ showDateTime($withdraw->created_at) }} <br> {{ diffForHumans($withdraw->created_at) }}
                                    </td>
                                    <td class="text-end text-md-center">
                                        <div class="td-wrapper">
                                            {{ $general->cur_sym }}{{ showAmount($withdraw->amount) }} - <span class="text--danger" title="@lang('charge')">{{ showAmount($withdraw->charge) }} </span>
                                            <br>
                                            <strong title="@lang('Amount after charge')">
                                                {{ showAmount($withdraw->amount - $withdraw->charge) }} {{ __($general->cur_text) }}
                                            </strong>
                                        </div>
                                    </td>
                                    <td class="text-end text-md-center">
                                        <div class="td-wrapper">
                                            1 {{ __($general->cur_text) }} = {{ showAmount($withdraw->rate) }} {{ __($withdraw->currency) }}
                                            <br>
                                            <strong>{{ showAmount($withdraw->final_amount) }} {{ __($withdraw->currency) }}</strong>
                                        </div>
                                    </td>
                                    <td class="text-end text-md-center">
                                        @php echo $withdraw->statusBadge @endphp
                                    </td>
                                    <td>
                                        <button class="btn btn--sm btn-outline--base detailBtn" data-user_data="{{ json_encode($withdraw->withdraw_information) }}" @if ($withdraw->status == Status::PAYMENT_REJECT) data-admin_feedback="{{ $withdraw->admin_feedback }}" @endif>
                                            <i class="la la-desktop"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($withdraws->hasPages())
                    {{ paginateLinks($withdraws) }}
                @endif
            @else
                @include($activeTemplate . 'partials.empty', ['message' => 'No payout yet!'])
            @endif
        </div>
    </div>

    <div class="modal fade" id="detailModal">
        <div class="modal-dialog modal-dialog-centered preview-modal">
            <div class="modal-content header-share">
                <button class="btn-close close-preview flex-center" data-bs-dismiss="modal" type="button" aria-label="Close"> <i
                       class="fas fa-times"></i></button>
                <div class="modal-body text-end text-md-center">
                    <h4 class="modal-title mb-2">@lang('Payout Details')</h4>
                    <ul class="list-group list-group-flush userData"></ul>
                    <div class="feedback"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.detailBtn').on('click', function() {
                var modal = $('#detailModal');
                var userData = $(this).data('user_data');
                var html = ``;
                userData.forEach(element => {
                    if (element.type != 'file') {
                        html += `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>${element.name}</span>
                            <span">${element.value}</span>
                        </li>`;
                    }
                });
                modal.find('.userData').html(html);

                if ($(this).data('admin_feedback') != undefined) {
                    var adminFeedback = `
                        <div class="my-3">
                            <strong>@lang('Admin Feedback')</strong>
                            <p>${$(this).data('admin_feedback')}</p>
                        </div>
                    `;
                } else {
                    var adminFeedback = '';
                }

                modal.find('.feedback').html(adminFeedback);

                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
