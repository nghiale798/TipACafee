@extends($activeTemplate . 'layouts.master')
@section('content')
<h2 class="page-title">{{ __($pageTitle) }}</h2>
<div class="multi-page-card">
    <div class="multi-page-card__body">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h5>@lang('Withdraw Via') {{ $withdraw->method->name }}</h5>
                <form action="{{ route('user.withdraw.submit') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-2">
                        @php
                            echo $withdraw->method->description;
                        @endphp
                    </div>
                    <x-viser-form identifier="id" identifierValue="{{ $withdraw->method->form_id }}" />
                    @if (auth()->user()->ts)
                        <div class="form-group">
                            <label>@lang('Google Authenticator Code')</label>
                            <input class="form-control form--control" name="authenticator_code" type="text" required>
                        </div>
                    @endif
                    <div class="form-group">
                        <button class="btn btn--base w-100" type="submit">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
