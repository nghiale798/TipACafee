@php
    $featureContent = getContent('feature.content', true);
    $featureElement = getContent('feature.element', orderById: true);
@endphp

<section class="feature-section py-60">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-heading">
                    <h2 class="section-heading__title">{{ __(@$featureContent->data_values->heading) }}</h2>
                    <p class="section-heading__desc">{{ __(@$featureContent->data_values->subheading) }}</p>
                </div>
            </div>
        </div>
        <div class="row gy-4 gy-md-4">
            @foreach ($featureElement as $feature)
                <div class="col-md-4 col-sm-6 col-xsm-6">
                    <div class="feature-card">
                        <span class="feature-card__thumb">
                            <img src="{{ getImage('assets/images/frontend/feature/' . @$feature->data_values->image, '50x50') }}" alt="">
                        </span>
                        <h4 class="feature-card__title">{{ __(@$feature->data_values->title) }}</h4>
                        <p class="feature-card__desc">{{ __(@$feature->data_values->description) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
