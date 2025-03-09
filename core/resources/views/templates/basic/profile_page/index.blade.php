@extends($activeTemplate . 'layouts.profile')
@section('content')
    @php
        session()->forget('DONATION_DATA');
        $coffee = @$donation->donation_price ?? $general->starting_price;
        $message = $donation->thanks_message ?? $general->thank_you_message;
        $welcomeMessage = @$user->membershipSetting?->welcome_message ?? $general->thank_you_message;
        $authUser = auth()->user();
    @endphp

    <div class="home-content template">
        <div class="template__inner py-44">
            <div class="row gy-4 justify-content-center">
                <div class="col-lg-7 order-1 order-lg-0">
                    @if (@$hasGoal)
                        @include($activeTemplate . 'profile_page.goal.index')
                    @endif
                    @if (blank($user->about))
                        @include($activeTemplate . 'profile_page.post.list')
                    @else
                        <div class="buy-coffee-card__list">
                            <span>@lang('Hey') 👋 @lang('I just created a page here. You can now buy me a')
                                {{ strtolower(__($user->donate_emoji_name)) }}!</span>
                        </div>
                        <div class="profile-about">
                            @php
                                echo $user->about;
                            @endphp
                            @if (@$user->website_link)
                                <div class="profile-about__website">
                                    <h6 class="title mb-0 mt-1">@lang('Follow-on')</h6>
                                    <a href="{{ $user->website_link }}" target="_blank"> {{ $user->website_link }}</a>
                                </div>
                            @endif

                            @if (!blank($user->social_links) && @$user->social_links)
                                <div class="profile-about__social">
                                    @foreach (@$user->social_links as $socialConnection)
                                        @php
                                            $parsedUrl = parse_url(@$socialConnection->link);
                                            $hostParts = explode('.', @$parsedUrl['host']);
                                            $domainName = @$hostParts[count(@$hostParts) - 2];
                                        @endphp
                                        <a class="footer-menu__social" href="{{ @$socialConnection->link }}" target="_blank"><i class="la las la-{{ @$domainName }}"></i></a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif

                    @if (count($recentSupports))
                        <div class="recent-supporter">
                            <h5 class="title">@lang('Recent Supporters')</h5>
                            <ul class="recent-supporter-list">
                                @foreach ($recentSupports as $supporter)
                                    @php
                                        $avatar = '';
                                        $profile = '';
                                        $profileUser = '';
                                        $qty = $supporter->quantity ?? 0;
                                        if (@$supporter->supporter) {
                                            $avatar = $supporter->supporter->image;
                                            $profile = $supporter->supporter->profile_link;
                                            $profileUser = $supporter->supporter;
                                        } else {
                                            $avatar = @$supporter->member->image;
                                            $profile = @$supporter->member->profile_link;
                                            $profileUser = @$supporter->member;
                                        }
                                    @endphp

                                    <li class="item">
                                        <span class="thumb"><img src="{{ avatar($avatar ? getFilePath('userProfile') . '/' . $avatar : null) }}" alt=""></span>
                                        <h6 class="name"><a href="{{ $profileUser ? route('home.page', $profile) : '#' }}">@lang('@'){{ @$profile ?? __('Someone') }}</a>
                                            @if (($qty && $supporter->supporter_id) || !$supporter->member_id)
                                                <span>@lang('bought') {{ $qty }} {{ __($user->donate_emoji_name) }}</span>
                                            @elseif($supporter->member_id)
                                                <span>@lang(' is now a member')</span>
                                            @endif
                                        </h6>
                                        @if (!$supporter->is_message_private && $supporter->message)
                                            <p class="comment">
                                                {{ __($supporter->message) }}
                                            </p>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <div class="col-lg-5 col-md-7 col-sm-10 order-0">
                    @include($activeTemplate . 'profile_page.support_member')
                </div>
            </div>
        </div>
    </div>

    @include($activeTemplate . 'profile_page.membership.modal')
    @include($activeTemplate . 'profile_page.post.thanks_modal')
    @include($activeTemplate . 'profile_page.goal.gift_tnx_modal')
    @include($activeTemplate . 'profile_page.membership.welcome_modal')

@endsection

@push('script')
    <script>
        "use strict";

        const isLoggedIn = "{{ auth()->check() }}";
        const likeURL = "{{ route('user.post.like') }}";

        $(document).ready(function() {
            var curSym = `{{ $general->cur_sym }}`;

            $(".coffee-no-of-cups .number-of-cup").on('click', function() {
                $(".coffee-no-of-cups .number-of-cup").removeClass("active");
                $(this).addClass("active");
                var donationAmount = $(this).data("daonation_amount");
                $('.donation-amount').text(curSym + donationAmount);
                $('.summation').val(donationAmount);
                $('[name=quantity]').val($(this).text());
            });

            $('[name=quantity]').on('input', function(event) {

                var number = $(this).val();
                var staringPrice = `{{ getAmount($coffee) }}`;

                if (number == '' || number == 0) {
                    $('.donation-amount').text(curSym + staringPrice);
                    $('.summation').val(staringPrice);
                } else {
                    var donationAmount = number * staringPrice;
                    $('.donation-amount').text(curSym + donationAmount);
                    $('.summation').val(donationAmount);
                }
            });

            //---------
            var userLink = @json(session('THANKS_LINK'));
            var welcome = @json(session('WELCOME_LINK'));
            var giftTnx = @json(session('THANKS_GOAL_LINK'));

            if (userLink) {
                $('#thankYouModal').modal('show');
                @php
                    session()->forget('THANKS_LINK');
                    session()->forget('quantity');
                @endphp
            }
            if (welcome) {
                $('#welcomeModal').modal('show');
                @php
                    session()->forget('WELCOME_LINK');
                    session()->forget('quantity');
                @endphp
            }
            if (giftTnx) {
                $('#giftTnxModal').modal('show');
                @php
                    session()->forget('THANKS_GOAL_LINK');
                @endphp
            }

        });
    </script>

    <script src="{{ asset($activeTemplateTrue . 'js/central-js.js') }}"></script>

@endpush
