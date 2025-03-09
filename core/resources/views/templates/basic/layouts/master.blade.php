@extends($activeTemplate . 'layouts.app')

@section('panel')
    @include($activeTemplate . 'partials.auth_header')
    <div class="template">
        <div class="template__inner">
            @include($activeTemplate . 'partials.auth_sidebar')
            <div class="template-body">
                @yield('content')
            </div>
        </div>
    </div>
@endsection
@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/jquery.validate.js') }}"></script>
@endpush
