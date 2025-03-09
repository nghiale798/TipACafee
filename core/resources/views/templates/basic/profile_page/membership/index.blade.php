@extends($activeTemplate . 'layouts.profile')
@section('content')
    @php
        $authUser = auth()->user();
    @endphp
    @if ($user->is_enable_membership && count($membershipLevel))
        <div class="gallery-content template">
            <div class="container">
                <div class="row d-flex justify-content-center pt-4 gy-3">
                    @if ($user->membershipSetting->is_show_count)
                        <div class="col-sm-6 col-md-5 col-lg-4">
                            <div class="membership-overview-card">
                                <div class="d-flex justify-content-between">
                                    <h4 class="membership-overview-card__title">{{ $totalCount }} </h4>
                                    <i class="las la-users fs-20 text--base"></i>
                                </div>
                                <span class="membership-overview-card__desc">@lang('Total Members')</span>
                            </div>
                        </div>
                    @endif
                    @if ($user->membershipSetting->is_show_earning)
                        <div class="col-sm-6 col-md-5 col-lg-4">
                            <div class="membership-overview-card">
                                <div class="d-flex justify-content-between">
                                    <h4 class="membership-overview-card__title">{{ $general->cur_sym }}{{ $amountOfCurrentMonth }} </h4>
                                    <i class="las la-hand-holding-usd fs-20 text--base"></i>
                                </div>
                                <span class="membership-overview-card__desc">@lang('Monthly Earning')</span>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="row my-3 gy-4 justify-content-center">
                    <h5 class="d-flex justify-content-center">@lang('Select a Membership Level')</h5>
                    @foreach ($membershipLevel as $level)
                        @php
                            $existMember = App\models\Membership::active()
                                ->where('user_id', $user->id)
                                ->where('member_id', @$authUser?->id)
                                ->where('membership_level_id', $level->id)
                                ->count();
                        @endphp
                        <div class="col-md-5 col-lg-4">
                            <div class="supporters-card pricing-card bg-white card border-0 h-100">
                                <img class="level-thumb" src="{{ photo(@$level->image ? getFilePath('membershipLevel') . '/' . @$level->image : null, false) }}" alt="">
                                <div class="card-header bg-transparent mt-2 border-0">
                                    <h3 class="fs-20 text-center fw-medium mb-2">{{ __($level->name) }}</h3>
                                    <h5 class="text-center m-0 fw-normal">{{ $general->cur_sym . showAmount($level->monthly_price) }}</h5>
                                </div>
                                <button
                                    class="w-100 btn mt-2 mx-2 mb-1 btn--outline btn--sm block w-auto joinMembershipBtn @if (!$authUser || $existMember >= 2) disabled @endif"
                                    data-levels="{{ @$level }}" type="button">
                                    {{ $existMember >= 2 ? __('Joined') : __('Join') }}
                                </button>
                                <div class="card-body">
                                    @include($activeTemplate . 'profile_page.membership.reward')
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @include($activeTemplate . 'profile_page.membership.modal');
    @endif
@endsection
