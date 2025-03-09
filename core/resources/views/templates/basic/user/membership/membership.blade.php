@extends($activeTemplate . 'layouts.master')
@section('content')
    @php
        $membershipContent = getContent('membership.content', true);
        $membershipElement = getContent('membership.element', limit: 2, orderById: true);
    @endphp
    <h2 class="page-title">{{ __($pageTitle) }}</h2>
    <div class="enable-membership">
        <div class="enable-membership__header">
            <h2 class="enable-membership__title mb-1">{{ __(@$membershipContent->data_values->heading) }}</h2>
            <p class="enable-membership__sub-title fs-16">{{ __(@$membershipContent->data_values->subheading) }}</p>
            <button class="btn btn--base enable-membership__enable-btn confirmationAltBtn" data-question="@lang('Are you sure to activate membership?')" data-action="{{ route('user.membership.enable') }}" type="button">
                @lang('Enable Membership')
                <span class="icon"><i class="fas fa-arrow-right"></i></span>
            </button>
        </div>
        <div class="enable-membership__content">
            <div class="feature-section">
                @foreach ($membershipElement as $key => $membership)
                    <div class="row flex-between @if ($key % 2 != 0) flex-row-reverse @endif">
                        <div class="col-sm-6">
                            <div class="feature-section__thumb">
                                <img src="{{ getImage('assets/images/frontend/membership/' . @$membership->data_values->image, '300x290') }}" alt="">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="feature-section__content mx-3">
                                <h4 class="feature-section__title">{{ __(@$membership->data_values->heading) }}</h4>
                                <p class="feature-section__desc">{{ __(@$membership->data_values->description) }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <x-confirmation-alert />
@endsection
