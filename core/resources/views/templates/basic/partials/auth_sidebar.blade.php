<div class="sidebar-menu flex-between">
    @php
        @$user = @auth()->user();
    @endphp

    <div class="sidebar-menu__inner">
        <span class="sidebar-menu__close d-lg-none d-block"><i class="fas fa-times"></i></span>
        <ul class="sidebar-menu-list">
            <li class="sidebar-menu-list__item {{ menuActive('user.home') }}">
                <a class="sidebar-menu-list__link" href="{{ route('user.home') }}">
                    <span class="icon">
                        <img src="{{ getImage($activeTemplateTrue . 'images/icons/home.png') }}" alt="Dashboard">
                    </span>
                    <span class="text">@lang('Dashboard')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item">
                <a class="sidebar-menu-list__link" href="{{ route('home.page', $user->profile_link) }}" target="_blank">
                    <span class="icon"><img src="{{ getImage($activeTemplateTrue . 'images/icons/layout.png') }}" alt="View Page"></span>
                    <span class="text">@lang('View Page') <i class="las la-external-link-alt"></i></span>
                </a>
            </li>
            <li class="sidebar-menu-list__item {{ menuActive(['user.explore.*']) }}">
                <a class="sidebar-menu-list__link" href="{{ route('user.explore.index') }}">
                    <span class="icon"><img src="{{ getImage($activeTemplateTrue . 'images/icons/explore.png') }}" alt="Explore Creators"></span>
                    <span class="text">@lang('Explore Creators')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item {{ menuActive(['user.donation.*']) }}">
                <a class="sidebar-menu-list__link" href="{{ route('user.donation.index') }}">
                    <span class="icon"> <img src="{{ getImage($activeTemplateTrue . 'images/icons/donation.png') }}" alt="Donation"></span>
                    <span class="text">@lang('Donation')</span>
                </a>
            </li>

            @php
                if (auth()->user()->is_enable_membership) {
                    $activeRoute = ['user.membership', 'user.membership.index', 'user.membership.level', 'user.membership.setting', 'user.membership.my.membership'];
                } else {
                    $activeRoute = ['user.membership', 'user.membership.index', 'user.membership.level', 'user.membership.setting'];
                }
            @endphp

            <li class="sidebar-menu-list__item {{ menuActive($activeRoute) }}">
                <a class="sidebar-menu-list__link" href="{{ route('user.membership.index') }}">
                    <span class="icon"><img src="{{ getImage($activeTemplateTrue . 'images/icons/membership.png') }}" alt="'Membership"></span>
                    <span class="text">@lang('Membership')</span>
                </a>
            </li>

            @if (!auth()->user()->is_enable_membership)
                <li class="sidebar-menu-list__item {{ menuActive(['user.membership.my.membership']) }}">
                    <a class="sidebar-menu-list__link" href="{{ route('user.membership.my.membership') }}">
                        <span class="icon"><img src="{{ getImage($activeTemplateTrue . 'images/icons/my-membership.png') }}" alt="my-Membership"></span>
                        <span class="text">@lang('My Membership')</span>
                    </a>
                </li>
            @endif

            <li class="sidebar-menu-list__item {{ menuActive(['user.goal.index', 'user.goal.gift.history']) }}">
                <a class="sidebar-menu-list__link" href="{{ route('user.goal.index') }}">
                    <span class="icon"><img src="{{ getImage($activeTemplateTrue . 'images/icons/goal.png') }}" alt="Goal"></span>
                    <span class="text">@lang('Set Goal')</span>
                </a>
            </li>

            <li class="sidebar-group-title">@lang('PUBLISH')</li>
            <li class="sidebar-menu-list__item {{ menuActive('user.post.*') }}">
                <a class="sidebar-menu-list__link" href="{{ route('user.post.index') }}">
                    <span class="icon">
                        <img src="{{ getImage($activeTemplateTrue . 'images/icons/post.png') }}" alt="Posts">
                    </span>
                    <span class="text">@lang('Posts')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item {{ menuActive('user.gallery.*') }}">
                <a class="sidebar-menu-list__link" href="{{ route('user.gallery.index') }}">
                    <span class="icon">
                        <img src="{{ getImage($activeTemplateTrue . 'images/icons/gallery.png') }}" alt="Gallery">
                    </span>
                    <span class="text">@lang('Gallery')</span>
                </a>
            </li>
            <li class="sidebar-group-title">@lang('FINANCE')</li>

            <li class="sidebar-menu-list__item {{ menuActive(['user.withdraw', 'user.withdraw.history']) }}">
                <a class="sidebar-menu-list__link" href="{{ route('user.withdraw') }}">
                    <span class="icon">
                        <img src="{{ getImage($activeTemplateTrue . 'images/icons/payout.png') }}" alt="Payout">
                    </span>
                    <span class="text">@lang('Payout Money')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item {{ menuActive(['user.deposit.history', 'user.deposit.index']) }}">
                <a class="sidebar-menu-list__link" href="{{ route('user.deposit.history') }}">
                    <span class="icon">
                        <img src="{{ getImage($activeTemplateTrue . 'images/icons/payment-history.png') }}" alt="Payment History">
                    </span>
                    <span class="text">@lang('Deposit History')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item {{ menuActive('user.payment.history') }}">
                <a class="sidebar-menu-list__link" href="{{ route('user.payment.history') }}">
                    <span class="icon">
                        <img src="{{ getImage($activeTemplateTrue . 'images/icons/payment.png') }}" alt="Payment History">
                    </span>
                    <span class="text">@lang('Payment History')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item {{ menuActive('user.transactions') }}">
                <a class="sidebar-menu-list__link" href="{{ route('user.transactions') }}">
                    <span class="icon">
                        <img src="{{ getImage($activeTemplateTrue . 'images/icons/transaction.png') }}" alt="Transactions">
                    </span>
                    <span class="text">@lang('Transactions')</span>
                </a>
            </li>
            <li class="sidebar-group-title">@lang('SETTING')</li>
            <li class="sidebar-menu-list__item d-lg-none d-block">
                <button class="sidebar-menu-list__link" data-bs-toggle="modal" data-bs-target="#editPage" type="button">
                    <span class="icon">
                        <img src="{{ getImage($activeTemplateTrue . 'images/icons/edit-alt.png') }}" alt="page-edit">
                    </span>
                    <span class="text">@lang('Edit Profile Page')</span>
                </button>
            </li>
            <li class="sidebar-menu-list__item d-lg-none d-block {{ menuActive('user.profile.setting') }}">
                <a class="sidebar-menu-list__link" href="{{ route('user.profile.setting') }}">
                    <span class="icon">
                        <img src="{{ getImage($activeTemplateTrue . 'images/icons/user.png') }}" alt="my-profile">
                    </span>
                    <span class="text">@lang('My Profile')</span>
                </a>
            </li>

            <li class="sidebar-menu-list__item d-lg-none d-block {{ menuActive('ticket.index') }}">
                <a class="sidebar-menu-list__link" href="{{ route('ticket.index') }}">
                    <span class="icon">
                        <img src="{{ getImage($activeTemplateTrue . 'images/icons/support-ticket.png') }}" alt="support-ticket">
                    </span>
                    <span class="text">@lang('Support')</span>
                </a>
            </li>

            <li class="sidebar-menu-list__item d-lg-none d-block {{ menuActive('user.change.password') }}">
                <a class="sidebar-menu-list__link" href="{{ route('user.change.password') }}">
                    <span class="icon">
                        <img src="{{ getImage($activeTemplateTrue . 'images/icons/padlock.png') }}" alt="password">
                    </span>
                    <span class="text">@lang('Password')</span>
                </a>
            </li>

            <li class="sidebar-menu-list__item {{ menuActive('user.twofactor') }}">
                <a class="sidebar-menu-list__link" href="{{ route('user.twofactor') }}">
                    <span class="icon">
                        <img src="{{ getImage($activeTemplateTrue . 'images/icons/2fa.png') }}" alt="2fa setting">
                    </span>
                    <span class="text">@lang('2FA Security')</span>
                </a>
            </li>
            <li class="sidebar-menu-list__item {{ menuActive('user.account.setting') }}">
                <a class="sidebar-menu-list__link" href="{{ route('user.account.setting') }}">
                    <span class="icon">
                        <img src="{{ getImage($activeTemplateTrue . 'images/icons/setting.png') }}" alt="setting">
                    </span>
                    <span class="text">@lang('Setting')</span>
                </a>
            </li>

            <li class="sidebar-menu-list__item d-lg-none d-block">
                <a class="sidebar-menu-list__link" href="{{ route('user.logout') }}">
                    <span class="icon">
                        <img src="{{ getImage($activeTemplateTrue . 'images/icons/out.png') }}" alt="logout">
                    </span>
                    <span class="text">@lang('Logout')</span>
                </a>
            </li>
        </ul>
    </div>
</div>
