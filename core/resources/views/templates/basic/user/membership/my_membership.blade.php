@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="multi-page-card">
        @include($activeTemplate . 'user.membership.navbar')
        <div class="multi-page-card__body pt-1 pb-1">
            <div class="row">
                @if (count(@$memberships))
                    <table class="table py-44 table table--responsive--sm">
                        <thead>
                            <tr>
                                <th>@lang('Recipient') | @lang('Level')</th>
                                <th>@lang('Donation') | @lang('Next Date')</th>
                                <th>@lang('Amount') | @lang('Member at')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($memberships as $membership)
                                <tr>
                                    <td>
                                        <div>
                                            <a href="{{ route('home.page', $membership->user->profile_link) }}" target="_blank">{{ __(@$membership->user->fullname) }} </a>
                                            <span class="text--primary">{{ @$membership->level->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            @php echo $membership->TYPEBadge; @endphp
                                            <br>
                                            <span class="text--primary">{{ showdateTime($membership->next_date) }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="text--primary">{{ $general->cur_sym }}{{ showAmount($membership->amount) }} </span> <br>
                                            {{ showdateTime($membership->create_at, 'd-M-Y') }}
                                        </div>
                                    </td>
                                    <td>@php echo $membership->statusBadge; @endphp</td>
                                    <td>
                                        <button title="@lang('Make Disabled')" class="btn btn--sm btn--danger confirmationAltBtn ms-auto" data-question="@lang('Are you sure to enable membership?')" data-action="{{ route('user.membership.activation.status', $membership->id) }}">
                                            <i class="la la-eye-slash"></i> @lang('Disabled')
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="no-donation-view">
                        <span class="no-donation-view__icon flex-center">
                            <i class="far fa-heart"></i>
                        </span>
                        <h5 class="no-donation-view__title">@lang('You didn\'t member of any user yet!')</h5>

                        <div class="no-donation-view__content">
                            <p class="no-donation-view__share">
                                <span class="share pageShareBtn">@lang('Share your page')</span>
                                @lang('with your audience and supporters.')
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <x-confirmation-alert />
@endsection

@push('style')
    <style>
        a:hover {
            color: hsl(var(--base));
        }
    </style>
@endpush
