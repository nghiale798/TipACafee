<div class="buy-coffee-card">
    @if ($user->is_enable_membership && count($membershipLevel))
        <ul class="nav nav-tabs custom--tab" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="supporters-tab" data-bs-toggle="tab" data-bs-target="#supporters-tab-pane" type="button" role="tab" aria-controls="supporters-tab-pane" aria-selected="true"><span class="icon pe-2"><i
                           class="far fa-heart"></i></span>
                    @lang('Support')
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="membership-tab" data-bs-toggle="tab" data-bs-target="#membership-tab-pane" type="button" role="tab" aria-controls="membership-tab-pane" aria-selected="false"> <span class="icon pe-2"><i class="fas fa-donate"></i></span>
                    @lang('Membersip')
                </button>
            </li>
        </ul>
    @endif

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="supporters-tab-pane" role="tabpanel" aria-labelledby="supporters-tab" tabindex="0">
            <form action="{{ route('payment.index') }}" method="post">
                @csrf
                <h3 class="buy-coffee-card__title">@lang('Buy') <span class="name">{{ __($user->fullname) }}</span> @lang('a')
                    {{ __(@$user->donate_emoji_name) }}
                </h3>
                <div class="buy-coffee-card__list">
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <span class="coffee-cup text--base">
                            {{ @$user->donate_emoji }}
                        </span>
                        <span class="times"><i class="la la-times"></i></span>
                    </div>
                    <ul class="coffee-no-of-cups">
                        <li class="number-of-cup flex-center" data-daonation_amount="{{ $coffee * 1 }}">1</li>
                        <li class="number-of-cup flex-center" data-daonation_amount="{{ $coffee * 2 }}">2</li>
                        <li class="number-of-cup flex-center" data-daonation_amount="{{ $coffee * 3 }}">3</li>
                        <li class="number-of-cup flex-center" data-daonation_amount="{{ $coffee * 4 }}">4</li>
                        <li class="number-of-cup flex-center" data-daonation_amount="{{ $coffee * 5 }}">5</li>
                        <li class="custom-cups"><input class="form--control" name="quantity" type="number" placeholder="20" /></li>
                    </ul>
                </div>
                <div class="form-group">
                    <input class="form--control" name="donor_identity" type="text" value="{{ old('donor_identity') }}" placeholder="@lang('Name or @yoursocial (optional)')">
                </div>
                <div class="form-group">
                    <textarea class="form--control mb-3" id="message" name="message" placeholder="@lang('Say something (optional)')">{{ old('message') }}</textarea>
                    <label class="privet-message form--check" for="privet-message">
                        <span class="custom--check">
                            <input class="form-check-input" id="privet-message" name="is_message_private" type="checkbox">
                        </span>
                        <span class="form-check-label">@lang('Make this message private?')</span>
                    </label>
                </div>
                @if (@$user?->donationSetting->cause_percent > 0)
                    <div class="donation-indicate flex-align gap-2 mb-4"><span class="icon"><img src="{{ getImage($activeTemplateTrue . 'images/icons/support.png') }}" alt=""></span>{{ getAmount($user->donationSetting->cause_percent) }}%
                        @lang('of all proceeds go to') {{ __($user->donationSetting->institute) }}</div>
                @endif
                <input class="summation" name="amount" type="hidden" value="{{ $coffee * 1 }}">
                <input name="user_id" type="hidden" value="{{ $user->id }}">

                <button class="btn btn--base w-100" @disabled(checkOwner($user->id))>@lang('Support') <span
                          class="donation-amount">{{ $general->cur_sym . getAmount(@$coffee) }}</span></button>
            </form>

            @if ($user->is_enable_membership && count($membershipLevel))
        </div>
        @endif
        @if ($user->is_enable_membership && count($membershipLevel))
            <div class="tab-pane fade " id="membership-tab-pane" role="tabpanel" aria-labelledby="membership-tab" tabindex="0">
                <div class="supporters-card">
                    @foreach ($membershipLevel as $level)
                        @php
                            $welcomeMessage = $level->welcome_msg ?? $general->thank_you_message;
                            $existMember = App\Models\Membership::active()
                                ->where('user_id', $user->id)
                                ->where('member_id', @$authUser?->id)
                                ->where('membership_level_id', $level->id)
                                ->count();
                        @endphp
                        <div class="card">
                            <div class="card-header bg-transparent">
                                <h3 class="fs-20 text-center fw-medium mb-1">{{ __($level->name) }}</h3>
                                <h5 class="text-center m-0 fw-normal">{{ $general->cur_sym . showAmount($level->monthly_price) }}</h5>
                            </div>
                            <div class="card-body">
                                <img class="level-thumb" src="{{ photo(@$level->image ? getFilePath('membershipLevel') . '/' . @$level->image : null, false) }}" alt="image">
                                @include($activeTemplate . 'profile_page.membership.reward')
                                <button
                                        class="btn mt-4 btn--outline btn--sm w-100 joinMembershipBtn @if (!@$authUser || @$existMember >= 2) disabled @endif" data-levels="{{ @$level }}" type="button">
                                    @if ($existMember >= 2)
                                        @lang('Joined')
                                    @else
                                        @lang('Join')
                                    @endif
                                </button>
                                @if (!@$authUser)
                                    <a class="d-flex justify-content-center text-decoration-underline mt-2" href="{{ route('user.login') }}">
                                        <small>@lang('Sign in First')</small>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @push('style')
            <style>
                .gallery li {
                    flex-grow: unset;
                    flex-shrink: unset;
                    margin-left: 0px;
                    margin-bottom: 0px;
                    position: relative;
                }
            </style>
        @endpush
