@extends($activeTemplate . 'layouts.master')
@section('content')
    <h2 class="page-title">{{ __($pageTitle) }}</h2>
    <div class="multi-page-card">
        @include($activeTemplate . 'user.donation.navbar')
        @include($activeTemplate . 'partials.support_index')
    </div>
@endsection
