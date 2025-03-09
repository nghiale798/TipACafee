@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="multi-page-card">
        <div class="multi-page-card__body">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <h4>@lang('KYC Form')</h4>
                    <hr>
                    <form action="{{ route('user.kyc.submit') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <x-viser-form identifier="act" identifierValue="kyc" />
                        <div class="form-group">
                            <button class="btn btn--base w-100" type="submit">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
