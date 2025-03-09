@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form action="" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group ">
                                    <label> @lang('Site Title')</label>
                                    <input class="form-control" name="site_name" type="text" value="{{ $general->site_name }}" required>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group ">
                                    <label>@lang('Currency')</label>
                                    <input class="form-control" name="cur_text" type="text" value="{{ $general->cur_text }}" required>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group ">
                                    <label>@lang('Currency Symbol')</label>
                                    <input class="form-control" name="cur_sym" type="text" value="{{ $general->cur_sym }}" required>
                                </div>
                            </div>
                            <div class="form-group col-md-4 col-sm-6">
                                <label> @lang('Timezone')</label>
                                <select class="select2-basic" name="timezone">
                                    @foreach ($timezones as $key => $timezone)
                                        <option value="{{ @$key }}">{{ __($timezone) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4 col-sm-6">
                                <label> @lang('Site Base Color')</label>
                                <div class="input-group">
                                    <span class="input-group-text p-0 border-0">
                                        <input class="form-control colorPicker" type='text' value="{{ $general->base_color }}" />
                                    </span>
                                    <input class="form-control colorCode" name="base_color" type="text" value="{{ $general->base_color }}" />
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label> @lang('Donation Starting Price')</label>
                                    <div class="input-group">
                                        <input class="form-control" name="starting_price" type="number" value="{{ @$general->starting_price }}" title="@lang('Containing default values are 1,2,3,4,5,10,15,20,25,30,40 or 50')" step="any" required>
                                        <span class="input-group-text">{{ __($general->cur_text) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label> @lang('Default Thank You Message')</label>
                                    <input class="form-control" name="thank_you_message" type="text" value="{{ @$general->thank_you_message }}" required>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label> @lang('Donate Emoji')</label>
                                    <input class="emoji-select form-control" name="emoji" type="text" value="{{ @$general->emoji }}" required>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label> @lang('Emoji Name')</label>
                                    <input class="form-control" name="emoji_name" type="text" value="{{ @$general->emoji_name }}" required>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label> @lang('Membership Monthly Level Amount')</label>
                                    <div class="input-group">
                                        <input class="form-control" name="monthly_level_amount" type="number" value="{{ @$general->monthly_level_amount }}" title="@lang('Containing monthly default values are 5,10 or 15')" step="any" required>
                                        <span class="input-group-text">{{ __($general->cur_text) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label> @lang('Membership Yearly Level Amount')</label>
                                    <div class="input-group">
                                        <input class="form-control" name="yearly_level_amount" type="number" value="{{ @$general->yearly_level_amount }}" title="@lang('Containing yearly default values are 50,100 or 500')" step="any" required>
                                        <span class="input-group-text">{{ __($general->cur_text) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .select2-container {
            z-index: 0 !important;
        }

        .emoji-select.form-control {
            height: 45px !important;
            border-radius: 5px;
        }

        .emoji-select.form-control>.emojionearea-editor {
            top: 7px !important;
        }

        .emojionearea .emojionearea-button>div {
            margin-top: 5px;
        }
    </style>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/spectrum.js') }}"></script>
    <script src="{{ asset('assets/global/js/emojionearea.min.js') }}"></script>
@endpush

@push('style-lib')
    <link href="{{ asset('assets/admin/css/spectrum.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/global/css/emojionearea.min.css') }}" rel="stylesheet" />
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.colorPicker').spectrum({
                color: $(this).data('color'),
                change: function(color) {
                    $(this).parent().siblings('.colorCode').val(color.toHexString().replace(/^#?/, ''));
                }
            });

            $('.colorCode').on('input', function() {
                var clr = $(this).val();
                $(this).parents('.input-group').find('.colorPicker').spectrum({
                    color: clr,
                });
            });

            $('select[name=timezone]').val("{{ $currentTimezone }}").select2();
            $('.select2-basic').select2({
                dropdownParent: $('.card-body')
            });


            //emoji
            $(document).ready(function() {
                $(".emoji-select").emojioneArea({
                    pickerPosition: "bottom",
                    filtersPosition: "bottom",
                    tonesStyle: "checkbox",
                    recentEmojis: false,
                    events: {
                        emojibtn_click: function(btn, event) {
                            this.setText($(event.target).closest('.emojibtn').data("name"));
                        }
                    }
                });

            });
            //emoji
        })(jQuery);
    </script>
@endpush
