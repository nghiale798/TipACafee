@extends($activeTemplate . 'layouts.app')
@section('panel')
    @include($activeTemplate . 'partials.header')
    @yield('content')
    @include($activeTemplate . 'partials.footer')
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('.s-highlight').each(function() {

                var $heading = $(this);
                var text = $heading.text();
                var words = text.split(' ');
                var sBreakValue = parseInt($heading.data('s-break'));
                var sLengthValue = parseInt($heading.data('s-length'));

                var breakIndex = sBreakValue < 0 ? words.length + sBreakValue : sBreakValue;

                var endIndex = sLengthValue ? breakIndex + sLengthValue : words.length;

                var coloredText = words.map(function(word, index) {
                    var colorClass = '';
                    if (index >= breakIndex && index < endIndex) {
                        colorClass = 'text--base';
                    }
                    return '<span class="' + colorClass + '">' + word + '</span>';
                }).join(' ');

                $heading.html(coloredText);
            });
        });
    </script>
@endpush
