@php
    $faqContent = getContent('faq.content', true);
    $faqElement = getContent('faq.element', orderById: true);
@endphp

<section class="faq py-60 @if(request()->routeIs('pages')) section-gap-heading @endif">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-heading faq-heading">
                    <h2 class="section-heading__title">{{ __(@$faqContent->data_values->heading) }}</h2>
                    <p class="section-heading__desc">{{ __(@$faqContent->data_values->subheading) }}</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8 col-sm-10">
                <div class="accordion custom--accordion" id="accordionExample">
                    @foreach ($faqElement as $faq)
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#collapse{{ $faq->id }}" type="button" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse{{ $faq->id }}">
                                    {{ __(@$faq->data_values->question) }}
                                </button>
                            </h2>
                            <div class="accordion-collapse collapse  @if ($loop->first) show @endif" id="collapse{{ $faq->id }}" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <p>@php echo @$faq->data_values->answer @endphp</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
