@extends($activeTemplate . 'layouts.' . $layout)
@section('content')
    <div class="container {{ $layout == 'frontend' ? 'py-120' : '' }}">
        <div class="row justify-content-center">
            <div class="col-lg-{{ $layout == 'frontend' ? 8 : 12 }}">
                <h2 class="page-title">{{ __($pageTitle) }}</h2>
                <div class="multi-page-card">
                    <div class="multi-page-card__body">
                        <form action="{{ route('user.deposit.manual.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="mb-4">@lang('You have requested') <b class="text--success">{{ showAmount($data['amount']) }}
                                            {{ __($general->cur_text) }}</b> , @lang('Please pay')
                                        <b class="text--success">{{ showAmount($data['final_amount']) . ' ' . $data['method_currency'] }} </b>
                                        @lang('for successful payment')
                                    </p>
                                    <h4 class="mb-4">@lang('Please follow the instruction below')</h4>

                                    <p class="my-4">@php echo  $data->gateway->description @endphp</p>

                                </div>

                                <x-viser-form identifier="id" identifierValue="{{ $gateway->form_id }}" />

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button class="btn btn--base w-100" type="submit">@lang('Pay Now')</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
