<header class="header" id="header">
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand logo" href="{{ route('home') }}"><img src="{{ siteLogo() }}" alt="{{ $general->site_name }}"></a>
            <button class="navbar-toggler header-button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" type="button" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span id="hiddenNav"><i class="las la-bars"></i></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav-menu ms-auto align-items-lg-center">
                    <li class="nav-item {{ menuActive('about') }}">
                        <a class="nav-link" href="{{ route('about') }}">@lang('About')</a>
                    </li>
                    <li class="nav-item {{ menuActive('contact') }}">
                        <a class="nav-link " href="{{ route('contact') }}">@lang('Contact')</a>
                    </li>
                    @auth
                        <li class="nav-item login-btn d-lg-block d-none">
                            <a class="nav-link flex-center" href="{{ route('user.home') }}">
                                @lang('Dashboard')
                                <span class="icon"><i class="fas fa-arrow-right"></i></span>
                            </a>
                        </li>
                    @else
                        <li class="nav-item login-btn d-lg-block d-none">
                            <a class="nav-link flex-center" href="{{ route('user.login') }}">
                                @lang('Sign in')
                                <span class="icon"><i class="fas fa-arrow-right"></i></span>
                            </a>
                        </li>
                    @endauth
                </ul>
                <div class="mobile-login d-lg-none d-flex flex-wrap justify-content-between align-items-center">
                    <ul class="login-registration-list d-flex flex-wrap align-items-center">
                        @auth
                            <li class="login-registration-list__item">
                                <a class="login-registration-list__link" href="{{ route('user.home') }}">
                                    <span class="login-registration-list__icon"><i class="fas fa-home"></i></span>
                                    @lang('Dashboard')
                                </a>
                            </li>
                            <li class="login-registration-list__item">
                                <a class="login-registration-list__link" href="{{ route('user.logout') }}">
                                    <span class="login-registration-list__icon"><i class="fas fa-sign-out-alt"></i></span>
                                    @lang('Logout')
                                </a>
                            </li>
                        @else
                            <li class="login-registration-list__item">
                                <a class="login-registration-list__link" href="{{ route('user.login') }}">
                                    <span class="login-registration-list__icon"><i class="fas fa-user"></i></span>
                                    @lang('Sign in')
                                </a>
                            </li>
                            <li class="login-registration-list__item">
                                <a class="login-registration-list__link" href="{{ route('user.register') }}">
                                    <span class="login-registration-list__icon"><i class="fas fa-user-plus"></i></span>
                                    @lang('Sign up')
                                </a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
    </div>  
</header>
<br/>