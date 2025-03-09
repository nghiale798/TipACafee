@php
    $fundingContent = getContent('funding.content', true);
    $fundingElement = getContent('funding.element', orderById: true);
@endphp
<div class="service sections-list py-60">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="section-heading style-left">
                    <h2 class="section-heading__title">{{ __(@$fundingContent->data_values->heading) }}</h2>
                    <p class="section-heading__desc">{{ __(@$fundingContent->data_values->subheading) }}</p>
                </div>
                <ul class="about-section__list">
                    @foreach ($fundingElement as $funding)
                        <li class="about-section__item">
                            <span class="icon"><img src="{{ getImage('assets/images/frontend/funding/' . @$funding->data_values->image, '40x40') }}" alt="@lang('image')"></span>
                            <p class="text">{{ __(@$funding->data_values->title) }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-6 align-self-center">
                <div class="about-section__thumb">
                    <img src="{{ getImage('assets/images/frontend/funding/' . @$fundingContent->data_values->image, '570x550') }}" alt="">
                </div>
            </div>
        </div>
    </div>
</div>
