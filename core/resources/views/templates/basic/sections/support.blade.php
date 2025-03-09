@php
    $supportContent = getContent('support.content', true);
    $supportElement = getContent('support.element', orderById: true);
@endphp

<div class="service sections-list py-60">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="section-heading style-left">
                    <h2 class="section-heading__title">{{ __(@$supportContent->data_values->heading) }}</h2>
                    <p class="section-heading__desc">{{ __(@$supportContent->data_values->subheading) }}</p>
                </div>
                <ul class="about-section__list">
                    @foreach ($supportElement as $support)
                        <li class="about-section__item">
                            <span class="icon">
                                <img src="{{ getImage('assets/images/frontend/support/' . @$support->data_values->image, '40x40') }}" alt=""></span>
                            <p class="text">{{ __(@$support->data_values->title) }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-6 align-self-center">
                <div class="about-section__thumb">
                    <img src="{{ getImage('assets/images/frontend/support/' . @$supportContent->data_values->image, '570x325') }}" alt="">
                </div>
            </div>
        </div>
    </div>
</div>
