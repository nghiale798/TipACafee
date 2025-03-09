<header class="dashboard-header" id="header">
    <div class="logo-section flex-align gap-4">
        <a class="logo" href="{{ route('home') }}">
            <img src="{{ siteLogo() }}" alt="{{ $general->site_name }}">
        </a>
    </div>
    <div class="user-menu">
        <button class="user-upload pageShareBtn">
            <span class="user-upload__icon"><img src="{{ getImage($activeTemplateTrue . 'images/icons/share.png') }}" alt=""></span>
        </button>
        @auth
            <button class="btn btn--outline editPage d-lg-block d-none" type="button">
                @lang('Edit page')
            </button>
        @endauth
        <a class="btn btn--base" href="{{ route('user.post.create') }}">
            <span class="icon-alt"><i class="fa fa-plus"></i></span>
            <span class="create-page-text">@lang('Create')</span>
        </a>
        <div class="user-info">
            @php
                $user = @auth()->user();
                $currentPath = request()->path();
                $uriSegments = explode('/', $currentPath)[0];
            @endphp
            @if ($uriSegments == 'user')
                <div class="navigation-bar d-lg-none d-block">
                    <div class="user-info__content ">
                        <span class="user-info__icon"><i class="fas fa-bars"></i> </span>
                        <div class="user-info__thumb">
                            <img src="{{ avatar(@$user->image ? getFilePath('userProfile') . '/' . $user->image : null) }}" alt="">
                        </div>
                    </div>
                </div>
                <button class="user-info__button d-lg-flex d-none" data-bs-toggle="dropdown" type="button" aria-expanded="false">
                    <span class="user-info__icon"><i class="fas fa-bars"></i> </span>
                    <span class="user-info__thumb">
                        <img src="{{ avatar(@$user->image ? getFilePath('userProfile') . '/' . $user->image : null) }}" alt="">
                    </span>
                </button>
            @else
                <button class="user-info__button" data-bs-toggle="dropdown" type="button" aria-expanded="false">
                    <span class="user-info__icon"><i class="fas fa-bars"></i> </span>
                    <span class="user-info__thumb">
                        <img src="{{ avatar(@$user->image ? getFilePath('userProfile') . '/' . $user->image : null) }}" alt="">
                    </span>
                </button>
            @endif
            <!-- Button for mobile Device only -->

            <ul class="user-info-dropdown">
                @if ($uriSegments == 'user')
                    <li class="user-info-dropdown__item">
                        <a class="user-info-dropdown__link" href="{{ route('home.page', $user->profile_link) }}" target="_blank">
                            <span class="icon"><i class="las la-cube"></i></span>
                            <span class="text">@lang('View My Page')</span>
                        </a>
                    </li>
                @endif
                <li class="user-info-dropdown__item">
                    <a class="user-info-dropdown__link" href="{{ route('user.home') }}">
                        <span class="icon"><i class="las la-tachometer-alt"></i></span>
                        <span class="text">@lang('Dashboard')</span>
                    </a>
                </li>
                <li class="user-info-dropdown__item">
                    <a class="user-info-dropdown__link" href="{{ route('user.profile.setting') }}">
                        <span class="icon"><i class="las la-user-circle"></i></span>
                        <span class="text">@lang('My Profile')</span>
                    </a>
                </li>
                <li class="user-info-dropdown__item">
                    <a class="user-info-dropdown__link" href="{{ route('user.change.password') }}">
                        <span class="icon"><i class="las la-lock"></i></span>
                        <span class="text">@lang('Password')</span>
                    </a>
                </li>
                <li class="user-info-dropdown__item">
                    <a class="user-info-dropdown__link" href="{{ route('ticket.index') }}">
                        <span class="icon"><i class="las la-ticket-alt"></i></span>
                        <span class="text">@lang('Support')</span>
                    </a>
                </li>
                <li class="user-info-dropdown__item">
                    <a class="user-info-dropdown__link" href="{{ route('user.logout') }}">
                        <span class="icon"><i class="las la-sign-in-alt"></i></span>
                        <span class="text">@lang('Logout')</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>

<div class="d-lg-none">
    @include($activeTemplate . 'partials.auth_sidebar')
</div>

@push('modal')
    <div class="modal fade" id="pageShareModal">
        <div class="modal-dialog modal-dialog-centered preview-modal">
            <div class="modal-content header-share">
                <button class="btn-close close-preview flex-center" data-bs-dismiss="modal" type="button" aria-label="Close"> <i
                       class="fas fa-times"></i></button>
                <div class="modal-body text-center">
                    <div class="modal-body__content share-action">
                        <h1 class="modal-body__title">@lang('Share') {{ __($user->fullname) }} @lang('\'s page')</h1>

                        @include($activeTemplate . 'partials.page_social_share')

                    </div>
                </div>
            </div>
        </div>
    </div>

    @include($activeTemplate . 'partials.edit_my_page')
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            $(".pageShareBtn").on('click', function() {
                var modal = $('#pageShareModal');
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
