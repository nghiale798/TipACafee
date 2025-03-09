@extends($activeTemplate . 'layouts.master')
@section('content')
<h2 class="page-title">{{ __($pageTitle) }}</h2>
    <div class="multi-page-card">
        <div class="multi-page-card__body">
            <div class="row justify-content-center">
                @if (!blank($transactions))
                    <div class="col-md-12">
                        <div class="show-filter mb-3 text-end">
                            <button class="btn btn--base showFilterBtn btn--sm" type="button"><i class="las la-filter"></i>@lang('Filter')</button>
                        </div>
                        <div class=" responsive-filter-card mb-4 border-0">
                            <form action="">
                                <div class="d-flex flex-wrap gap-4">
                                    <div class="flex-grow-1">
                                        <label>@lang('Transaction Number')</label>
                                        <input class="form-control form--control" name="search" type="text" value="{{ request()->search }}">
                                    </div>
                                    <div class="flex-grow-1">
                                        <label>@lang('Type')</label>
                                        <select class="form--select form--control" name="trx_type">
                                            <option value="">@lang('All')</option>
                                            <option value="+" @selected(request()->trx_type == '+')>@lang('Plus')</option>
                                            <option value="-" @selected(request()->trx_type == '-')>@lang('Minus')</option>
                                        </select>
                                    </div>
                                    <div class="flex-grow-1">
                                        <label>@lang('Remark')</label>
                                        <select class="form--select form--control" name="remark">
                                            <option value="">@lang('Any')</option>
                                            @foreach ($remarks as $remark)
                                                <option value="{{ $remark->remark }}" @selected(request()->remark == $remark->remark)>{{ __(keyToTitle($remark->remark)) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="flex-grow-1 align-self-end">
                                        <button class="btn btn--base w-100"><i class="las la-filter"></i> @lang('Filter')</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="verticalResponsiveTable">
                            <table class="table table table--responsive--sm">
                                <thead>
                                    <tr>
                                        <th>@lang('Trx')</th>
                                        <th>@lang('Transacted')</th>
                                        <th>@lang('Amount')</th>
                                        <th>@lang('Post Balance')</th>
                                        <th>@lang('Detail')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $trx)
                                        <tr>
                                            <td>
                                                <strong>{{ $trx->trx }}</strong>
                                            </td>

                                            <td>
                                                {{ showDateTime($trx->created_at) }}<br>{{ diffForHumans($trx->created_at) }}
                                            </td>

                                            <td class="budget">
                                                <span class="fw-bold @if ($trx->trx_type == '+') text--success @else text--danger @endif">
                                                    {{ $trx->trx_type }} {{ showAmount($trx->amount) }} {{ $general->cur_text }}
                                                </span>
                                            </td>

                                            <td class="budget">
                                                {{ $general->cur_sym }}{{ showAmount($trx->post_balance) }}
                                            </td>

                                            <td>{{ __($trx->details) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($transactions->hasPages())
                            <div class="mt-3">
                                {{ paginateLinks($transactions) }}
                            </div>
                        @endif

                    </div>
                @else
                @include($activeTemplate . 'partials.empty', ['message' => 'No transaction found!'])
                @endif
            </div>
        </div>
    </div>
@endsection
