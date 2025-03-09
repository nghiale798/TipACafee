@extends($activeTemplate . 'layouts.' . $layout)
@section('content')
    <div class="container {{ $layout == 'frontend' ? 'py-120' : '' }}">
        <div class="row justify-content-center">
            <div class="col-lg-{{ $layout == 'frontend' ? 8 : 12 }}">
                <h2 class="page-title">{{ __($pageTitle) }}</h2>
                <div class="multi-page-card">
                    <div class="multi-page-card__body">
                        <ul class="list-group  list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                @lang('You have to pay '):
                                <strong>{{showAmount($deposit->final_amount)}} {{__($deposit->method_currency)}}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                @lang('You will get '):
                                <strong>{{showAmount($deposit->amount)}}  {{__($general->cur_text)}}</strong>
                            </li>
                        </ul>
                        <form action="{{$data->url}}" method="{{$data->method}}">
                            <input type="hidden" custom="{{$data->custom}}" name="hidden">
                            <script src="{{$data->checkout_js}}"
                                    @foreach($data->val as $key=>$value)
                                    data-{{$key}}="{{$value}}"
                                @endforeach >
                            </script>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $('input[type="submit"]').addClass("mt-4 btn btn--base w-100");
        })(jQuery);
    </script>
@endpush
