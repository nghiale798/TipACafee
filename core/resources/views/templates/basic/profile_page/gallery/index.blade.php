@extends($activeTemplate . 'layouts.profile')
@section('content')
    @php
        $coffee = @$user->donationSetting->donation_price ?? $general->starting_price;
        $authUser = auth()->user();
    @endphp
    <div class="gallery-content template">
        <div class="template__inner py-44">
            @if (!blank($galleryImages))
                <ul class="list list--row flex-images gallery flex-center" id="flexBox">
                    @foreach ($galleryImages as $gallery)
                        @php
                            $defaultImage = asset('assets/images/default.png');
                            $imageUrl = getImage(getFilePath('gallery') . '/' . $gallery->image, getFileSize('gallery'));
                            $support = $authUser?->supporter->where('status', Status::ENABLE)->count();
                            $member = $authUser?->myMemberships()->where('status', Status::ENABLE)->whereHas('level', function ($query) {
                                    $query->where('status', Status::ENABLE);
                                })->count();
                            $mySupporter = $gallery->visible == Status::VISIBLE_SUPPORTER && $support;
                            $myMember = $gallery->visible == Status::VISIBLE_MEMBER && $member;
                        @endphp
                        <li class="item" data-w="{{ $gallery->image_width }}" data-h="{{ $gallery->image_height }}">
                            @if ($gallery->visible == Status::VISIBLE_PUBLIC || $myMember || $mySupporter || $user->id == @$authUser?->id)
                                <a class="gallery__link" href="{{ route('gallery.view', [$user->profile_link, $gallery->slug, $gallery->id]) }}">
                                    <img class="gallery__img lazy-loading-img" data-image_src="{{ $imageUrl }}" src="{{ $defaultImage }}" alt="@lang('Image')">
                                    <figure>
                                        <figcaption class="gallery__content">
                                            <span class="gallery__title">
                                                {{ __($gallery->title) }}
                                            </span>
                                            <span class="gallery__footer">
                                                <span class="gallery__like">
                                                    <span class="gallery__like-num">{{ shortNumber($gallery->total_view) }}</span>
                                                    <span class="gallery__like-icon">
                                                        @lang('views')
                                                    </span>
                                                </span>

                                                <span class="gallery__like">
                                                    <span class="gallery__like-icon">
                                                        <i class="fas fa-heart"></i>
                                                    </span>

                                                    <span class="gallery__like-num count">
                                                        {{ shortNumber($gallery->likes_count) }}
                                                    </span>
                                                </span>
                                            </span>
                                        </figcaption>
                                    </figure>
                                </a>

                                <div class="gallery__share">
                                    <div class="list gallery__list">
                                        <div>
                                            @auth
                                                @php
                                                    $like = $gallery->isLikedBy(auth()->user());
                                                @endphp
                                                <button class="gallery__btn like-btn" data-gallery_id ="{{ $gallery->id }}" title="@if ($like) @lang('Unlike') @else @lang('like') @endif">
                                                    <span class="icon">

                                                        @if ($like)
                                                            <i class="fas fa-heart"></i>
                                                        @else
                                                            <i class="far fa-heart"></i>
                                                        @endif
                                                    </span>
                                                </button>
                                            @else
                                                <span class="icon">
                                                    <a class="user-login" href="{{ route('user.login') }}" target="_blank">
                                                        <i class="far fa-heart"></i>
                                                    </a>
                                                </span>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            @elseif ($gallery->visible == Status::VISIBLE_MEMBER && !$myMember)
                                <div class="visible-member">
                                    <a href="{{ route('gallery.view', [$user->profile_link, $gallery->slug, $gallery->id]) }}">
                                        <span class="icon"><img src="{{ getImage($activeTemplateTrue . 'images/icons/member-lock.png') }}" alt=""></span>
                                        <span class="title pt-2">@lang('Members only')</span>
                                    </a>
                                </div>
                            @elseif ($gallery->visible == Status::VISIBLE_SUPPORTER && !$mySupporter)
                                <div class="visible-member visible-supporter">
                                    <a href="{{ route('gallery.view', [$user->profile_link, $gallery->slug, $gallery->id]) }}">
                                        <span class="icon"><img src="{{ getImage($activeTemplateTrue . 'images/icons/supporter-lock.png') }}" alt=""></span>
                                        <span class="title pt-2">@lang('Supporters only')</span>
                                    </a>
                                </div>
                            @endif

                        </li>
                    @endforeach
                </ul>
            @else
                <div class="row gy-4 justify-content-center">
                    <div class="col-lg-5 col-md-7 col-sm-10">
                        @include($activeTemplate . 'partials.empty', ['message' => 'No gallery photo found!'])
                    </div>
                    <div class="col-lg-5 col-md-7 col-sm-10 order-1">
                        @include($activeTemplate . 'profile_page.support_member')
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('style')
    <style>
        .spinner-border {
            width: 1rem;
            height: 1rem;
        }

        .comment-edit-wrapper {
            padding-left: 45px;
        }

        .modal-body__img {
            width: 80px;
            height: 80px;
            border-radius: 100%;
            overflow: hidden;
            margin: 0 auto;
        }
    </style>
@endpush

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/jquery.flex-images.min.js') }}"></script>
@endpush
@push('script')
    <script>
        "use strict";
        $('#flexBox').flexImages({
            rowHeight: 240
        });
        const isLoggedIn = "{{ auth()->check() }}";
        const likeURL = "{{ route('user.gallery.like') }}";
    </script>
    <script src="{{ asset($activeTemplateTrue . 'js/central-gallery-react.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';

        $(document).ready(function() {
            var curSym = `{{ $general->cur_sym }}`;

            $(".coffee-no-of-cups .number-of-cup").on('click', function() {
                $(".coffee-no-of-cups .number-of-cup").removeClass("active");
                $(this).addClass("active");
                var donationAmount = $(this).data("daonation_amount");
                $('.donation-amount').text(curSym + donationAmount);
                $('.summation').val(donationAmount);
                $('[name=quantity]').val($(this).text());
            });

            $('[name=quantity]').on('input', function(event) {
                var number = $(this).val();
                var staringPrice = `{{ getAmount($coffee) }}`;

                if (number == '' || number == 0) {
                    $('.donation-amount').text(curSym + staringPrice);
                    $('.summation').val(staringPrice);
                } else {
                    var donationAmount = number * staringPrice;
                    $('.donation-amount').text(curSym + donationAmount);
                    $('.summation').val(donationAmount);
                }
            });
        });
    </script>
@endpush
