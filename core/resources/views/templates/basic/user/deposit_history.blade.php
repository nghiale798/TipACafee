@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="d-flex gap-2 flex-wrap justify-content-between align-items-center mb-4">
        <h2 class="page-title mb-0">{{ __($pageTitle) }}</h2>
        <a class="btn btn--base btn--sm" href="{{ route('user.deposit.index') }}"> <i class="las la-credit-card"></i> @lang('Deposit Now')</a>
    </div>
    <div class="multi-page-card">
        <div class="multi-page-card__body">
            <div class="row">
                <div class="col-12">
                    <div class="search-header d-flex">
                        <form action="" method="">
                            <div class="d-flex justify-content-end">
                                <div class="input-group">
                                    <input class="form-control form--control" name="search" type="text" value="{{ request()->search }}" placeholder="@lang('Search here')">
                                    <button class="input-group-text btn">
                                        <i class="las la-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                    <div class="project-area py-4">
                        @if (blank($deposits))
                            @include($activeTemplate . 'partials.empty', ['message' => 'No deposit found!'])
                        @else
                            <div class="verticalResponsiveTable">
                                <table class="table table table--responsive--sm">
                                    <thead>
                                        <tr>
                                            <th>@lang('Gateway | Transaction')</th>
                                            <th>@lang('Initiated')</th>
                                            <th>@lang('Amount')</th>
                                            <th>@lang('Conversion')</th>
                                            <th>@lang('Status')</th>
                                            <th>@lang('Action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($deposits as $deposit)
                                            <tr>
                                                <td>
                                                    <div>
                                                        {{ __($deposit->gateway?->name) }}
                                                        <br>
                                                        <small class="border-effect-bottom"> {{ $deposit->trx }} </small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        {{ showDateTime($deposit->created_at) }}<br>{{ diffForHumans($deposit->created_at) }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        {{ $general->cur_sym }}{{ showAmount($deposit->amount) }} +
                                                        <span class="text--danger" title="@lang('charge')">{{ showAmount($deposit->charge) }}
                                                        </span>
                                                        <br>
                                                        <strong title="@lang('Amount with charge')">
                                                            {{ $general->cur_sym }}{{ showAmount($deposit->amount + $deposit->charge) }}
                                                        </strong>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <span class="border-effect-bottom">{{ $general->cur_sym }}1 =
                                                            {{ $general->cur_sym }}{{ showAmount($deposit->rate) }}</span>
                                                        <br>
                                                        <strong>{{ $general->cur_sym }}{{ showAmount($deposit->final_amount) }}</strong>
                                                    </div>
                                                </td>
                                                <td>
                                                    @php echo $deposit->statusBadge @endphp
                                                </td>
                                                @php
                                                    $details = $deposit->detail != null ? json_encode($deposit->detail) : null;
                                                @endphp
                                                <td>
                                                    <button
                                                            class="btn btn--sm btn--base invest-details-link @if ($deposit->method_code >= 1000) detailBtn @else disabled @endif" type="button" @if ($deposit->method_code >= 1000) data-info="{{ $details }}" @endif @if ($deposit->status == Status::PAYMENT_REJECT) data-admin_feedback="{{ $deposit->admin_feedback }}" @endif>
                                                        <i class="las la-desktop"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if ($deposits->hasPages())
                                <div class="mt-3">
                                    {{ paginateLinks($deposits) }}
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="detailModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <div class="d-flex justify-content-between">
                        <h5 class="mb-2">@lang('Details')</h5>
                        <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                    </div>
                    <ul class="list-group userData list-group-flush">
                    </ul>
                    <div class="feedback py-2"></div>
                    <div class="text-end">
                        <button class="btn btn--sm btn-dark" data-bs-dismiss="modal" type="button">@lang('Close')</button>
                    </div>
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

                var userData = $(this).data('info');
                var html = '';
                if (userData) {
                    userData.forEach(element => {
                        if (element.type != 'file') {
                            html += `
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>${element.name}</span>
                                <span">${element.value}</span>
                            </li>`;
                        }
                    });
                }

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
