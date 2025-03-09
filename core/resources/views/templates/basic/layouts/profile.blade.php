@extends($activeTemplate . 'layouts.app')

@section('panel')
    @auth
        @include($activeTemplate . 'partials.auth_header')
    @else
        @include($activeTemplate . 'partials.header')
    @endauth
    <div class="user-profile">
        <div class="profile-template @if (auth()->user()) auth @endif">
             <div class="profile-template__header">
                @include($activeTemplate . 'profile_page.banner')
            </div>
            <div class="profile-template__body">
                @include($activeTemplate . 'profile_page.navbar')
                @yield('content')
            </div> 
        </div>
    </div>
    @include($activeTemplate . 'partials.footer')
@endsection

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/jquery.validate.js') }}"></script>
@endpush

@if (@$user?->theme_color)
    @php
        $cssColorCode = $user->theme_color;
        if (substr($cssColorCode, 0, 1) === '#') {
            $baseColor = substr($cssColorCode, 1);
        }
    @endphp
    @push('style-color')
        <style>
            :root {
                --base-h: <?php echo hexToHsl($baseColor)['h']; ?>;
                --base-s: <?php echo hexToHsl($baseColor)['s']; ?>%;
                --base-l: <?php echo hexToHsl($baseColor)['l']; ?>%;
            }
        </style>
    @endpush
@endif
