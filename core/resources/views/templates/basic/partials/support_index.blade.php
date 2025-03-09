<div class="multi-page-card__body">
    @if ($countDonation)
        <div class="donation-overview-card-list justify-content-center">
            <div class="donation-overview-card">
                <h5 class="donation-overview-card__title"> {{ $countDonation }}</h5>
                <div class="donation-overview-card__content flex-wrap">
                    @if (@$isMember)
                        <span class="icon"><i class="las la-unlock-alt"></i></span>
                        @lang('Member')
                    @else
                        <span class="icon"><i class="far fa-heart"></i></span>
                        @lang('Supporter')
                    @endif
                </div>
            </div>
            <div class="donation-overview-card">
                <h5 class="donation-overview-card__title">
                    <span class="icon">{{ $general->cur_sym }}</span>{{ showAmount($lastThirtyDaysDonation) }}
                </h5>
                <div class="donation-overview-card__content flex-wrap">
                    <span class="icon"><i class="far fa-calendar-alt"></i></span>
                    @lang('Last 30 Days')
                </div>
            </div>
            <div class="donation-overview-card">
                <h5 class="donation-overview-card__title">
                    <span class="icon">{{ $general->cur_sym }}</span>{{ showAmount($totalDonation) }}
                </h5>
                <div class="donation-overview-card__content flex-wrap">
                    <span class="icon"><i class="las la-hand-holding-usd"></i></span>
                    @lang('All - Time')
                </div>
            </div>
        </div>

        <form class="d-flex flex-wrap gap-2 filter mt-5" action="" method="GET">
                <select class="form--control form--select donation_time" name="doantion_filter">
                    <option value="all" selected>@lang('Filter all time')</option>
                    <option value="week" @selected(request()->doantion_filter == 'week')>@lang('Filter by current week')</option>
                    <option value="month" @selected(request()->doantion_filter == 'month')>@lang('Filter by current month')</option>
                    <option value="year" @selected(request()->doantion_filter == 'year')>@lang('Filter by current year')</option>
                </select>
        </form>

        <table class="table py-44 table table--responsive--md">
            <thead>
                <tr>
                    <th>@lang('Support at')</th>
                    @if (@!$isMember)
                        <th>@lang('By')</th>
                    @endif
                    <th>@lang('Donor')</th>
                    <th>@lang('Amount')</th>
                    @if (@$isMember)
                        <th>@lang('Type')</th>
                        <th>@lang('Next Date')</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($donations as $donation)
                    <tr>
                        <td>{{ showDateTime($donation->created_at, 'd, M Y') }}</td>
                        @if (@!$isMember)
                            <td>
                                @if (@$donation->supporter_id)
                                    @lang('Supporter')
                                @else
                                    @lang('Member')
                                @endif
                            </td>
                        @endif
                        <td>
                            {{ $donation->donor_identity ? __($donation->donor_identity) : $donation->member->fullname ?? ($donation->donor->fullname ?? __('Anonymous')) }}
                        </td>
                        <td>{{ $general->cur_sym }}{{ showAmount($donation->amount) }} </td>
                        @if (@$isMember)
                            <td>
                                @php echo $donation->typeBadge; @endphp
                            </td>
                            <td>{{ showDateTime($donation->next_date, 'd, M Y') }} </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if ($donations->hasPages())
            <div class="mt-3">
                {{ paginateLinks($donations) }}
            </div>
        @endif
    @else
        <div class="no-donation-view">
            <span class="no-donation-view__icon flex-center">
                <i class="far fa-heart"></i>
            </span>
            @if (@$isMember)
                <h5 class="no-donation-view__title">@lang('You don\'t have any membership yet!')</h5>
            @else
                <h5 class="no-donation-view__title">@lang('You don\'t have any supporters yet!')</h5>
            @endif
            <div class="no-donation-view__content">
                <p class="no-donation-view__share">
                    <span class="share pageShareBtn">@lang('Share your page')</span>
                    @lang('with your audience and supporters.')
                </p>
            </div>
        </div>
    @endif
</div>

@push('script')
    <script>
        $(document).ready(function() {
            $('.donation_time').on('change', function() {
                let form = $('.filter');
                form.submit();
            });
        });
    </script>
@endpush
