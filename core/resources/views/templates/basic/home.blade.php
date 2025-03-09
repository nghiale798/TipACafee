@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <main class="landing-main">
        @include($activeTemplate . 'partials.banner')
        @if (@$sections->secs != null)
            @foreach (json_decode($sections->secs) as $sec)
                @include($activeTemplate . 'sections.' . $sec)
            @endforeach
        @endif
    </main>
@endsection
