@extends($activeTemplate . 'layouts.app')
@section('panel')
    @php
        $banned = getContent('banned.content', true);
    @endphp
    <div class="maintenance-page flex-column justify-content-center my-auto">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-8 text-center">
                    <div class="row justify-content-center">
                        <div class="col-xl-10">
                            <h3 class="text--danger mb-2">{{ __(@$banned->data_values->heading) }}</h3>
                        </div>
                        <div class="col-sm-6 col-8 col-lg-12">
                            <img class="img-fluid mx-auto mb-5" src="{{ getImage('assets/images/frontend/banned/' . @$banned->data_values->image, '360x370') }}" alt="@lang('image')">
                        </div>
                    </div>
                    <p class="mx-auto mb-4 text-center">
                      @lang('Ban Reason'):{{ __($user->ban_reason) }} 
                    </p>
                    <a class="btn mx-auto  btn--base btn--sm" href="{{ route('home') }}"> 
                       <i class="las la-globe"></i> @lang('Home') 
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style-lib')
    <link href="{{ asset($activeTemplateTrue . 'css/main.css') }}" rel="stylesheet">
@endpush
@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/main.js') }}"></script>
@endpush
