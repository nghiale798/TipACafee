@php
    $howWorkElement = getContent('how_work.element', orderById: true);
@endphp
<section class="how-it-work py-60 bg-white">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="work-slider">
                    @foreach ($howWorkElement as $work)
                        <div class="work-slider-item">
                            <div class="work-slider-item__content">
                                <h3 class="work-slider-item__title"> {{ __(@$work->data_values->title) }}</h3>
                                <p class="work-slider-item__desc"> {{ __(@$work->data_values->description) }}</p>
                            </div>
                            <div class="work-slider-item__thumb">
                                <img src="{{ getImage('assets/images/frontend/how_work/' . @$work->data_values->image, '1000x600') }}"
                                    alt="work-slider">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
