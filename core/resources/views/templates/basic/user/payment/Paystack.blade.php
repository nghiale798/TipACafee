@extends($activeTemplate . 'layouts.' . $layout)
@section('content')
    <div class="container {{ $layout == 'frontend' ? 'py-60' : '' }}">
        <div class="row justify-content-center">
            <div class="col-lg-{{ $layout == 'frontend' ? 8 : 12 }}">
                <h2 class="page-title">{{ __($pageTitle) }}</h2>
                <div class="multi-page-card">
                    <div class="multi-page-card__body">
                        <form action="{{ route('ipn.'.$deposit->gateway->alias) }}" method="POST" class="text-center">
                            @csrf
                            <ul class="list-group text-center list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    @lang('You have to pay '):
                                    <strong>{{ showAmount($deposit->final_amount) }} {{ __($deposit->method_currency) }}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    @lang('You will get '):
                                    <strong>{{ showAmount($deposit->amount) }} {{ __($general->cur_text) }}</strong>
                                </li>
                            </ul>
                            <button type="button" class="btn btn--base w-100 mt-3" id="btn-confirm">@lang('Pay Now')</button>

                            <script src="//js.paystack.co/v1/inline.js" data-key="{{ $data->key }}" data-email="{{ $data->email }}" data-amount="{{ round($data->amount) }}"
                                data-currency="{{ $data->currency }}" data-ref="{{ $data->ref }}" data-custom-button="btn-confirm"></script>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
