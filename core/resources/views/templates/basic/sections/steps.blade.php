@php
    $stepsContent = getContent('steps.content', true);
    $stepsElement = getContent('steps.element', orderById: true);
@endphp

<section class="steps py-60 section-bg">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-heading">
                    <h2 class="section-heading__title">{{ __(@$stepsContent->data_values->heading) }}</h2>
                    <p class="section-heading__desc">{{ __(@$stepsContent->data_values->subheading) }}</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="step-list">
                    <div class="row">
                        @foreach ($stepsElement as $step)
                            <div class="col-md-7 col-sm-10 col-11">
                                <div class="step">
                                    <h4 class="step__title">{{ __(@$step->data_values->title) }}</h4>
                                    <div class="step-card">
                                        <div class="step-card__thumb">
                                            <img src="{{ asset($activeTemplateTrue . 'shapes/arrow-shap.svg') }}" alt="image">
                                        </div>
                                        <div class="step-card__content">
                                            <span class="icon flex-center">
                                                <img src="{{ getImage('assets/images/frontend/steps/' . @$step->data_values->image, '50x50') }}" alt="{{ __($step->data_values->title) }}">
                                            </span>
                                            <div class="text">
                                                <h5 class="step-card__title">{{ __(@$step->data_values->heading) }}</h5>
                                                <p class="step-card__desc">{{ __(@$step->data_values->description) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
