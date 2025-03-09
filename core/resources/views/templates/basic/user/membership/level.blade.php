@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="multi-page-card">
        @include($activeTemplate . 'user.membership.navbar')
        <div class="multi-page-card__body">
            <div class="row gy-4 mb-4">
                @forelse ($levels as $level)
                    @php
                        $membersDonations = $level->memberships;
                        $thisLevelMembersCount = $level->memberships()->select('member_id')->distinct()->count('member_id');
                        $thisLevelMembersEarnMonthly = $level->monthly_price * $thisLevelMembersCount;
                    @endphp
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="flex-between gap-2">
                                    <h6 class="mb-0 fs-14 flex-1">{{ __($level->name) }}</h6>
                                    @if ($level->status == 0)
                                        <button class="flex-shrink-0 btn btn--outline follow-button--sm text--success confirmationAltBtn" data-action="{{ route('user.membership.level.status', $level->id) }}" data-question="@lang('Are you sure to enable this level?')" type="button"><i class="las la-toggle-off"></i> @lang('Enable')</button>
                                    @else
                                        <button class="flex-shrink-0 btn btn--outline follow-button--sm text--danger confirmationAltBtn" data-action="{{ route('user.membership.level.status', $level->id) }}" data-question="@lang('Are you sure to disabled this level?')" type="button"><i class="las la-toggle-on"></i> @lang('Disable')</button>
                                    @endif
                                </div>

                                <div class="flex-align gap-3">
                                    <small><i class="las la-donate"></i> {{ $general->cur_sym . getAmount($thisLevelMembersEarnMonthly) }}
                                        @lang('per month')</small>
                                    <small><i class="las la-user-tie"></i> {{ $thisLevelMembersCount }} @lang('members')</small>
                                </div>

                            </div>
                            <div class="card-body">
                                <img class="level-thumb" src="{{ photo(@$level->image ? getFilePath('membershipLevel') . '/' . @$level->image : null, false) }}" alt="">
                                <p class="fs-14">{{ __($level->description) }}</p>
                                <h6 class="mb-0 fs-14">@lang('REWARDS') &#x2192;</h6>
                                <ul class="reward-card__list">
                                    <li class="preview-card__list-items">
                                        <span class="icon"><i class="las la-check"></i></span>
                                        <p class="text one fs-14">{{ __($level->rewards->one) }}</p>
                                    </li>
                                    <li class="preview-card__list-items">
                                        <span class="icon"><i class="las la-check"></i></span>
                                        <p class="text two fs-14">{{ __($level->rewards->two) }}</p>
                                    </li>
                                    <li class="preview-card__list-items">
                                        <span class="icon"><i class="las la-check"></i></span>
                                        <p class="text three fs-14">{{ __($level->rewards->three) }}</p>
                                    </li>
                                </ul>
                                <a class="btn my-4 btn--outline btn--sm w-100" href="{{ route('user.membership.edit.level', $level->id) }}">@lang('Edit')</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        @include($activeTemplate . 'partials.empty', ['message' => 'No level found!'])
                    </div>
                @endforelse
            </div>
            <div class="setting-list  d-flex justify-content-center">
                <a class="btn btn--base w-50" href="{{ route('user.membership.new.level') }}">
                    <span><i class="fa fa-plus"></i> @lang('Add Level')</span>
                </a>
            </div>
        </div>
    </div>
@endsection

<x-confirmation-alert />
