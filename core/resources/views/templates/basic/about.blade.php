@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @php
        $aboutContent = getContent('about_us.content', true);
    @endphp
    <section class="about-section py-60 section-gap-heading">
        <div class="container">
            <div class="row align-items-center gy-3 justify-content-between">
                <div class="col-lg-6">
                    <div class="about-section__content mb-4">
                        <h3 class="about-section__title mb-3">{{ __(@$aboutContent->data_values->heading) }}</h3>
                        <p class="about-section__desc">@php echo @$aboutContent->data_values->description; @endphp</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-section__thumb text-md-center text-lg-end text-start">
                        <img src="{{ getImage('assets/images/frontend/about_us/' . @$aboutContent->data_values->image, '636x505') }}"
                            alt="about-section-thumb">
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif

@endsection
