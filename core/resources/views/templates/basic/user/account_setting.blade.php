@extends($activeTemplate . 'layouts.master')
@section('content')
<h2 class="page-title">{{ __($pageTitle) }}</h2>
    <div class="multi-page-card">
        <div class="multi-page-card__body">
            <div class="setting-list">
                <div class="setting-list__header flex-align pt-0">
                    <h5 class="setting-list__title">@lang('My Page Link')</h5>
                </div>
                <div class="setting-list__body">
                    <div class="copy-link input-group mt-4">
                        <span class="input-group-text fs-16"><i class="las la-link"></i></span>
                        <input class="form-control form--control fs-14 ps-0" type="text" value="{{ route('home.page', $user->profile_link) }}" readonly>
                        <span class="input-group-text flex-align copy-btn fs-14 cursor-pointer" id="copyBtn" data-link="{{ route('home.page', $user->profile_link) }}"><i class="far fa-copy"></i>&nbsp; @lang('Copy')</span>
                    </div>
                </div>
            </div>

            <div class="setting-list pb-0">
                <div class="setting-list__header flex-between pb-0">
                    <h5 class="setting-list__title">@lang('Display Supporter Count')</h5>
                    <div class="setting-list__check">
                        <label class="form--switch">
                            <input name="show_supporter_count" type="checkbox" @if (auth()->user()->show_supporter_count) checked @endif>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <p class="setting-list__desc">
                    @lang('Showing your supporter & member count might encourage more people to join.')
                </p>
            </div>
        </div>
    </div>

    <div class="delete-list">
        <h5 class="setting-list__title">@lang('Disable Account')</h5>
        <div class="setting-list__body flex-between">
            <p class="setting-list__desc">@lang('Your account will be temporarily deactivated and will not be accessible publicly. You will be logged out in the process, and the page will be re-activated when you login again.')</p>
            <button class="text-center mt-3"><span class="btn btn--danger  confirmationAltBtn" data-question="@lang('You will no longer receive payments. This will not affect your earnings so far.')" data-action="{{ route('user.account.deactivate') }}">@lang('Deactivate')</span></button>
        </div>
    </div>

    <x-confirmation-alert />
@endsection

@push('script')
    <script>
        'use strict';
        $(document).ready(function() {
            $('[name="show_supporter_count"]').on('change', function() {
                var isChecked = $(this).prop('checked');
                var url = '{{ route('user.account.setting.store') }}';
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        show_supporter_count: isChecked ? 1 : 0,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.success)
                            if (isChecked) {
                                notify('success', 'Enable display supporter on your profile');
                            } else {
                                notify('error', 'Disabled display supporter on your profile');
                            }
                    }
                });
            })
        });
    </script>
@endpush
