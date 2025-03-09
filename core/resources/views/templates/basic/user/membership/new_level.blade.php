@extends($activeTemplate . 'layouts.app')
@section('panel')
    <div class="template new-membership-level-page">
        <div class="template__inner">
            <div class="row align-items-center mb-4 mb-md-0">
                <div class="col-md-8 col-sm-12">
                    <a class="btn back-btn btn-outline--light" href="{{ route('user.membership.level') }}">
                        <i class="fas fa-arrow-left"></i>
                        @lang('Level')
                    </a>
                </div>
                <div class="col-md-4 col-sm-6">
                    <h5 class="membership-card-content__title mb-0">@lang('LEVEL PREVIEW')</h5>
                </div>
            </div>
            <form action="{{ route('user.membership.level.store', @$level->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row gy-4 justify-content-center">
                    <div class="col-md-8 col-sm-12 order-1 order-md-0">
                        <div class="membership-content">
                            <div class="membership-content__list">
                                <h6 class="membership-content__title">@lang('Membership Level Name')</h6>
                                <input class="form--control" name="level_name" type="text" value="{{ old('level_name', @$level->name) }}"
                                    placeholder="@lang('Standard Level')" required>
                            </div>
                            <div class="membership-content__list">
                                <h6 class="membership-content__title">@lang('Price')</h6>
                                <div class="row gy-3">
                                    <div class="col-sm-6">
                                        <div class="input-group membership-content__price">
                                            <span class="input-group-text">@lang('Monthly')</span>
                                            <input class="form-control form--control monthly_price" name="monthly_price" type="number"
                                            value="{{ old('monthly_price', @getAmount($level->monthly_price)) }}" required>
                                            <span class="input-group-text">{{ __($general->cur_text) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="input-group membership-content__price">
                                            <span class="input-group-text">@lang('Yearly')</span>
                                            <input class="form-control form--control yearly_price" name="yearly_price" type="number"
                                            value="{{ old('yearly_price', @getAmount($level->yearly_price)) }}" required>
                                            <span class="input-group-text">{{ __($general->cur_text) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="membership-content__list">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h6 class="membership-content__title">@lang('Image')</h6>
                                        <div class="membership-content__desc">
                                            <p>@lang('An image is optional, Yet it can enhance your branding or increase the appeal of your offering.')
                                            </p>
                                            <p>@lang('Recommended size'): {{ getFileSize('membershipLevel') }}@lang('px')</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 ">
                                        <label class="membership-content__img-upload flex-center">
                                            <input class="default-file-input" id="file-input" name="level_image" type="file">
                                            @if (@$level)
                                                <span class="icon levelPicPreview"></span>
                                                <img class="edit-lv-img"
                                                    src="{{ getImage(getFilePath('membershipLevel') . '/' . @$level->image, getFileSize('membershipLevel')) }}"
                                                    alt="">
                                            @else
                                                <span class="icon levelPicPreview"><i class="fas fa-cloud-upload-alt"></i></span>
                                            @endif
                                            <span class="title">@lang('Upload Image')</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="membership-content__list">
                                <h6 class="membership-content__title">@lang('Description')</h6>
                                <textarea class="form--control" name="description" required>{{ old('description', @$level->description) }}</textarea>
                            </div>
                            <div class="membership-content__list">
                                <h6 class="membership-content__title">@lang('Rewards')</h6>
                                <div class="reward-card">
                                    <div class="reward-items">
                                        <input class="reward-input" name="rewards[]" type="hidden" value="Support me on a monthly basis.">
                                        <span class="icon"><i class="las la-check"></i></span>
                                        <p class="text rone">
                                            @if (@$level)
                                                {{ __(@$level->rewards->one) }}
                                            @else
                                                @lang('Support me on a monthly basis.')
                                            @endif
                                        </p>
                                        <span class="rewardEditBtn" title="@lang('Edit Reward')"><i class="las la-pen-square"></i></span>
                                    </div>
                                    <div class="reward-items">
                                        <input class="reward-input" name="rewards[]" type="hidden" value="Unlock exclusive posts and messages.">
                                        <span class="icon"><i class="las la-check"></i></span>
                                        <p class="text rtwo">
                                            @if (@$level)
                                                {{ __(@$level->rewards->two) }}
                                            @else
                                                @lang('Unlock exclusive posts and messages.')
                                            @endif
                                        </p>
                                        <span class="rewardEditBtn" title="@lang('Edit Reward')"><i class="las la-pen-square"></i></span>
                                    </div>
                                    <div class="reward-items">
                                        <input class="reward-input" name="rewards[]" type="hidden" value="Explore premium gallery with content.">
                                        <span class="icon"><i class="las la-check"></i></span>
                                        <p class="text rthree">
                                            @if (@$level)
                                                {{ __(@$level->rewards->three) }}
                                            @else
                                                @lang('Explore premium gallery with content.')
                                            @endif
                                        </p>
                                        <span class="rewardEditBtn" title="@lang('Edit reward')"><i class="las la-pen-square"></i></span>
                                    </div>
                                </div>

                            </div>

                            <div class="membership-content__list welcome-note">
                                <h6 class="membership-content__title">@lang('Welcome Note')</h6>
                                <p class="membership-content__desc">@lang('After the payment, this information will be visible both in the welcome email and on the platform. Personalize it and include any links to rewards you wish to share.')</p>
                                <textarea class="form--control" name="welcome_msg" required>{{ @$level->welcome_msg ? @$level->welcome_msg : 'Thank you for joining my membership!🎉' }}</textarea>
                            </div>
                            <button class="btn btn--base w-100 d-block">@lang('Submit')</button>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-8 order-0 order-md-1">
                        <div class="membership-card-content">
                            <div class="preview-card">
                                <h4 class="preview-card__title">{{ __(@$level->name) }}</h4>
                                <div class="preview-card__price">
                                    <span class="preview-price">
                                        @if (@$level)
                                            {{ $general->cur_sym . getAmount($level->monthly_price) }}
                                        @else
                                            {{ $general->cur_sym . getAmount(gs('monthly_level_amount')) }}
                                        @endif
                                    </span>
                                    <span class="times">@lang('PER MONTH')</span>
                                </div>
                                <div class="preview-card__btn">
                                    <button class="btn btn--base w-100" type="button">@lang('Join')</button>
                                </div>
                                <ul class="preview-card__list">
                                    <li class="preview-card__list-items">
                                        <span class="icon"><i class="las la-check"></i></span>
                                        <p class="text one">
                                            @if (@$level)
                                                {{ __(@$level->rewards->one) }}
                                            @else
                                                @lang('Support me on a monthly basis.')
                                            @endif
                                        </p>
                                    </li>
                                    <li class="preview-card__list-items">
                                        <span class="icon"><i class="las la-check"></i></span>
                                        <p class="text two">
                                            @if (@$level)
                                                {{ __(@$level->rewards->two) }}
                                            @else
                                                @lang('Unlock exclusive posts and messages.')
                                            @endif
                                        </p>
                                    </li>
                                    <li class="preview-card__list-items">
                                        <span class="icon"><i class="las la-check"></i></span>
                                        <p class="text three">
                                            @if (@$level)
                                                {{ __(@$level->rewards->three) }}
                                            @else
                                                @lang('Explore premium gallery with content.')
                                            @endif
                                        </p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- modal --}}
    <div class="modal fade confirm-modal custom--modal" id="rewardEditModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <button class="btn-close close-preview flex-center" data-bs-dismiss="modal" type="button" aria-label="Close"> <i
                        class="fas fa-times"></i></button>
                <div class="modal-body text-center">
                    <h4 class="modal-title">@lang('Custom Reward')</h4>
                    <div class="membership-content__list my-3">
                        <input class="form--control update-reward" type="text">
                    </div>
                    <button class="btn btn--base w-100 save-reward" type="button">@lang('Save Reward')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        "use strict";
        $(document).ready(function() {
            var fileInput = $("[name=level_image]");
            var levelPicPreview = $(".levelPicPreview");
            fileInput.on('change', function() {
                var file = this.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var image = $("<img>");
                        image.attr("src", e.target.result);
                        image.css({
                            "max-width": "200px",
                            "max-height": "150px"
                        });
                        $('.edit-lv-img').addClass('d-none');
                        levelPicPreview.html(image);
                    };
                    reader.readAsDataURL(file);
                }
            });

            $("[name=level_name]").on('input', function() {
                if ($(this).length) {
                    $(".preview-card__title").text($(this).val());
                } else {
                    $(".preview-card__title").text(`@lang('LEVEL 1')`);
                }
            });
            let monthlyDefault = '{{ gs('monthly_level_amount') }}';
            let yearlyDefault = '{{ gs('yearly_level_amount') }}';

            @if (@empty($level))
                $("[name=monthly_price]").val(monthlyDefault);
                $("[name=yearly_price]").val(yearlyDefault);
                $(".preview-card__title").text(`@lang('LEVEL 1')`);
            @endif

            $("[name=monthly_price]").on('input', function() {
                $("[name=yearly_price]").val($(this).val() * 10);
                var monthlyPrice = $(this).val();
                var curSym = `{{ $general->cur_sym }}`;
                if (monthlyPrice) {
                    $(".preview-price").text(curSym + monthlyPrice);
                } else {
                    $(".preview-price").text(curSym + monthlyDefault);
                }
            });

            $(document).on('keypress', "[name=monthly_price], [name=monthly_price]", function(e) {
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
            });

            $(document).on('click', '.rewardEditBtn', function() {
                var reward = $(this).closest('.reward-items').find('.text')
                var text = reward.text().trim();
                var input = $(this).closest('.reward-items').find('.reward-input')
                input.addClass('rewardValue');
                reward.addClass('thisOne');

                var modal = $('#rewardEditModal');
                modal.find('.update-reward').val(text);
                modal.modal('show');
            });

            $(document).on('click', '.save-reward', function() {
                var modal = $('#rewardEditModal');
                var text = modal.find('.update-reward').val();

                var reward = $('.reward-items').find('.thisOne');
                reward.text(text)
                reward.removeClass('thisOne');
                var input = $('.reward-items').find('.rewardValue');
                input.removeClass('rewardValue');
                input.val(text);
                //s//
                var rone = $('.reward-items').find('.rone').text();
                $('.preview-card__list-items').find('.one').text(rone);
                var rtwo = $('.reward-items').find('.rtwo').text();
                $('.preview-card__list-items').find('.two').text(rtwo);
                var rthree = $('.reward-items').find('.rthree').text();
                $('.preview-card__list-items').find('.three').text(rthree);
                //e//
                modal.modal('hide');
            });

            $('#rewardEditModal').on('hide.bs.modal', function() {
                $('.reward-items').find('.thisOne').removeClass("thisOne");
                $('.reward-items').find('.rewardValue').removeClass("rewardValue");
            })


        });
    </script>
@endpush
