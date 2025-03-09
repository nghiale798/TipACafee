@extends($activeTemplate . 'layouts.profile')
@section('content')
    @php
        $coffee = @$user->donationSetting->donation_price ?? $general->starting_price;
        $authUser = auth()->user();
        $support = $authUser?->supporter->where('status', Status::ENABLE)->count();
        $member = $authUser?->myMemberships()->where('status', Status::ENABLE)->whereHas('level', function ($query) {
                $query->where('status', Status::ENABLE);
            })->count();
        $mySupporter = $gallery->visible == Status::VISIBLE_SUPPORTER && $support;
        $myMember = $gallery->visible == Status::VISIBLE_MEMBER && $member;
    @endphp

    <div class="post-content template">
        <div class="template__inner py-44">
            <div class="row gy-4 justify-content-center">
                <div class="col-lg-7 order-0 order-lg-0">
                    <div class="tab-content-list">
                        <div class="post-details-card gallery-details">
                            <div class="post-details__head flex-between">
                                <div class="authour-info flex-align">
                                    <span class="thumb">
                                        <img src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, getFileSize('userProfile')) }}" alt="">
                                    </span>
                                    <div class="author-content gallery-author">
                                        <h6 class="author-name">{{ $user->fullname }}</h6>
                                        <div class=" flex-align">
                                            <small class="publish-date fs-14">{{ showDateTime(@$gallery->created_at, 'M d, Y') }}</small>
                                            @if ($gallery->visible == Status::VISIBLE_SUPPORTER)
                                                <div class="target-audience flex-align">
                                                    <span class="icon"><i class="las la-unlock-alt"></i></span>
                                                    <p class="text">@lang('Supporters Only')</p>
                                                </div>
                                            @elseif($gallery->visible == Status::VISIBLE_MEMBER)
                                                <div class="target-audience flex-align">
                                                    <span class="icon"><i class="las la-unlock-alt"></i></span>
                                                    <p class="text">@lang('Members Only')</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="header-left flex-align">
                                    <p class="views-data fs-14">{{ $gallery->total_view }} @lang('Views')</p>
                                    @if ($gallery->visible == Status::VISIBLE_PUBLIC || $myMember || $mySupporter || $user->id == @$authUser?->id)
                                        <div class="post-list-card__option">
                                            <div class="dropdown">
                                                <span class="icon" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-h"></i>
                                                </span>
                                                <ul class="dropdown-menu post-list-card__action p-2 py-3 gallery-view">
                                                    <li class="action-list cursor-pointer confirmationAltBtn mb-2" data-question="@lang('Are you sure to download this photo?')" data-action="{{ route('user.gallery.download.image', $gallery->id) }}"><i
                                                           class="las la-download"></i> <span>@lang('Download')</span></li>
                                                    @if ($user->id == @$authUser?->id)
                                                        <li class="action-list text--danger cursor-pointer confirmationAltBtn" data-question="@lang('Are you sure to delete this galley photo?')" data-action="{{ route('user.gallery.delete', $gallery->id) }}"><span><i
                                                                   class="las la-trash-alt"></i> @lang('Delete')</span></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="post-details__body">
                                @if ($gallery->visible == Status::VISIBLE_PUBLIC || $myMember || $mySupporter || @$user?->id == @$authUser?->id)
                                    <div class="post-img">
                                        <img class="thumb" src="{{ getImage(getFilePath('gallery') . '/' . $gallery->image, getFileSize('gallery')) }}" alt="" loading="lazy">
                                    </div>
                                    <h5 class="title mt-4 mb-1">{{ __(@$gallery->title) }}</h5>
                                    <p class="text">@php echo __(strip_tags(@$gallery->content));@endphp</p>
                                @elseif ($gallery->visible == Status::VISIBLE_MEMBER && !$myMember)
                                    <div class="visible-member">
                                        <span class="icon"><img src="{{ getImage($activeTemplateTrue . 'images/icons/member-lock.png') }}" alt=""></span>
                                        <h4 class="title pt-2">@lang('Members only')</h4>
                                        @if (!auth()->check())
                                            <p class="member-login">@lang('Already a member?')<a href="{{ route('user.login') }}">@lang('Sign in')</a></p>
                                        @else
                                            <a class="btn join-btn cursor-none  btn--outline Btn @disabled(checkOwner($user->id))" href="{{ route('membership.page', $user->profile_link) }}">@lang('Join as member') </a>
                                            <small>@lang('Choice a best level for membership!')</small>
                                        @endif
                                    </div>
                                @elseif ($gallery->visible == Status::VISIBLE_SUPPORTER && !$mySupporter)
                                    <div class="visible-member">
                                        <span class="icon"><img src="{{ getImage($activeTemplateTrue . 'images/icons/supporter-lock.png') }}" alt=""></span>
                                        <p class="title pt-2">@lang('Supporters only')</p>
                                        @if (!auth()->check())
                                            <p class="member-login">@lang('Already a supporter?')<a href="{{ route('user.login') }}"> @lang('Sign in')</a></p>
                                        @else
                                            <button class="btn join-btn btn--outline supportBtn @disabled(checkOwner($user->id))">@lang('Support')
                                                {{ $general->cur_sym }}{{ getAmount($coffee) }}</button>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="post-details__footer">
                                <div class="react-list flex-align">
                                    <div class="like flex-align gap-2">
                                        @auth
                                            <span class="icon like-btn" data-gallery_id="{{ @$gallery->id }}">
                                                @if ($gallery->isLikedBy($authUser))
                                                    <i class="fas fa-heart icf"></i>
                                                @else
                                                    <i class="far fa-heart"></i>
                                                @endif
                                            </span>
                                        @else
                                            <span class="icon">
                                                <a class="user-login" href="{{ route('user.login') }}" target="_blank">
                                                    <i class="far fa-heart"></i>
                                                </a>
                                            </span>
                                        @endauth
                                        <sapn class="count">
                                            @if ($gallery->likes_count)
                                                {{ $gallery->likes_count }}
                                            @endif
                                        </sapn>
                                    </div>

                                    @if ($gallery->comments_count)
                                        <div class="comment flex-align gap-2">
                                            <span class="icon">
                                                @auth
                                                    @if ($gallery->isCommentedBy($authUser))
                                                        <i class="fas fa-comment-alt icf"></i>
                                                    @else
                                                        <i class="far fa-comment-alt"></i>
                                                    @endif
                                                @else
                                                    <i class="far fa-comment-alt"></i>
                                                @endauth
                                            </span>
                                            <sapn class="count">{{ $gallery->comments_count }}</sapn>
                                        </div>
                                    @endif
                                    <div class="share flex-align gap-2 ms-auto">
                                        <div class="dropdown">
                                            <span class="icon" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                                @lang('Share') <i class="far fa-share-square"></i>
                                            </span>
                                            <div class="dropdown-menu share-action dropdown-menu-end">
                                                <div class="copy-link input-group">
                                                    <span class="input-group-text fs-16"><i class="las la-link"></i></span>
                                                    <input class="form-control form--control copy-input fs-14" type="text" value="{{ url()->current() }}" disabled>
                                                    <span class="input-group-text flex-align copy-btn fs-14" id="copyBtn" data-link="{{ route('gallery.view', [$user->profile_link, $gallery->slug, $gallery->id]) }}"><i
                                                           class="far fa-copy"></i> @lang('Copy')</span>
                                                </div>
                                                <div class="social-list  gap-2 flex-wrap">
                                                    <a class="social-btn facebook flex-align" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank"><i class="fab fa-facebook-f"></i> @lang('Facebook')</a>
                                                    <a class="social-btn linkedin flex-align" href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ $gallery->slug }}&amp;summary={{ $gallery->content }}" target="_blank"><i class="fab fa-linkedin-in"></i>@lang('Linkedin')</a>
                                                    <a class="social-btn twitter flex-align" href="https://twitter.com/intent/tweet?text={{ $gallery->slug }}&amp;url={{ urlencode(url()->current()) }}" target="_blank"><i class="fab fa-twitter"></i>@lang('Twitter')</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if ($gallery->visible == Status::VISIBLE_PUBLIC || $myMember || $mySupporter || $user->id == @$authUser?->id)
                                    <div class="comments">
                                        <div class="send-comment flex-align gap-3">
                                            <div class="profile-thumb">
                                                <img src="{{ avatar(@$authUser->image ? getFilePath('userProfile') . '/' . $authUser->image : null) }}" alt="">
                                            </div>
                                            <form class="send__comment" id="commentForm">
                                                <div class="input-group">
                                                    <textarea class="form--control post-comment" id="comment" name="comment" type="text" @if (!auth()->check()) disabled @endif placeholder="@lang('Write comment')..."></textarea>
                                                    <button class="btn--base btn-sm comment-send-btn" type="submit" @if (!auth()->check()) disabled @endif><i class="las la-paper-plane"></i></button>
                                                </div>
                                                @if (!auth()->check())
                                                    <small><a class="underline" href="{{ route('user.login') }}">@lang('Sign in')</a>
                                                        @lang('or') <a class="underline" href="{{ route('user.register') }}">@lang('Sign up')</a> @lang('to leave a comment.')</small>
                                                @endif
                                            </form>
                                        </div>
                                        <div class="commentContainer">
                                            @include($activeTemplate . 'profile_page.post.comment')
                                        </div>

                                        @if ($gallery->comments && @$gallery->comments->count() > 10)
                                            <button class="btn btn--sm btn-outline--base ms-auto loadMoreComment " type="button">
                                                @lang('More')...
                                            </button>
                                        @endif
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 col-md-7 col-sm-10 order-0">
                    @include($activeTemplate . 'profile_page.support_member')
                </div>

            </div>
        </div>
    </div>

    @include($activeTemplate . 'profile_page.membership.modal')
    @include($activeTemplate . 'profile_page.supporter_modal')

    <x-confirmation-alert />
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

@push('script')
    <script>
        'use strict';
        const isLoggedIn = "{{ auth()->check() }}";
        const likeURL = "{{ route('user.gallery.like') }}";

        $(document).ready(function() {

            $("#comment").keydown(function(event) {
                var textarea = $("#comment");
                textarea.css('height', "50px");
                var height = $("#comment").prop("scrollHeight");

                if (event.keyCode === 13 && !event.shiftKey) {
                    event.preventDefault();
                    $("#commentForm").submit();
                } else if (event.keyCode === 13 && event.shiftKey) {
                    textarea.css('height', `${height}px`);
                } else {
                    textarea.css('height', `${height}px`);
                }
            });

            $(".supportBtn").on('click', function() {
                var modal = $('#supportModal');
                modal.modal('show');
            });

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



            //start-inputValueCheck//
            checkCommentInput();
            $('#comment').on('input', function() {
                checkCommentInput();
            });

            function checkCommentInput() {
                var comment = $('#comment').val();
                if (comment.trim() !== '') {
                    $('.comment-send-btn').prop('disabled', false);
                } else {
                    $('.comment-send-btn').prop('disabled', true);
                }
            }
            //end-inputValueCheck//

            //comment-store//
            if (isLoggedIn) {
                $('#commentForm').on('submit', function(e) {
                    e.preventDefault();

                    var btn = $('.comment-send-btn');
                    var formData = new FormData($('#commentForm')[0]);
                    var url = '{{ route('user.gallery.comment', $gallery->id) }}';
                    var token = '{{ csrf_token() }}';

                    formData.append('_token', token);

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function() {
                            btn.html(`<div class="spinner-border"></div>`);
                            btn.attr('disabled', true);
                        },
                        complete: function() {
                            btn.html(`<i class="las la-paper-plane"></i>`);
                            btn.removeAttr('disabled');
                        },
                        success: function(response) {
                            $('[name="comment"]').val('');
                            if (response.success) {
                                $('.commentContainer').prepend(response.html);
                            } else {
                                notify('error', response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            notify('error', error);
                        }
                    });
                });
            }

            $("body").on("keydown", ".edit-comment", function(event) {
                if (event.keyCode === 13 && !event.shiftKey) {
                    event.preventDefault();
                    $(".commentEditForm").submit();
                }
            });

            //edit-comment-here//
            if (isLoggedIn) {
                $('body').on('submit', '.commentEditForm', function(e) {

                    e.preventDefault();

                    var parentDiv = $(this).parents('.comment-list');
                    var commentId = parentDiv.find('[name=comment_id]').val();
                    var commentData = parentDiv.find('[name=comment]').val();

                    var btnAfterSubmit = `<div class="spinner-border"></div>`;
                    var btnName = `<i class="las la-paper-plane"></i>`;
                    var btn = $(this).find('.comment-edit-send-btn');
                    btn.html(btnAfterSubmit);
                    btn.attr('disabled', true);
                    var commentContainer = $(this).closest('.post-comment');

                    //store
                    var url = '{{ route('user.gallery.comment', $gallery->id) }}';
                    var token = '{{ csrf_token() }}';
                    var formData = {
                        _token: token,
                        comment_id: commentId,
                        comment: commentData
                    };

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: formData,
                        success: function(response) {
                            if (response.success) {
                                btn.html(btnName);
                                btn.removeAttr('disabled');
                                parentDiv.find('.comment-edit-wrapper').addClass('d-none');
                                parentDiv.find('.post-comment-text').removeClass('d-none').text(response.comment);
                            } else {
                                notify('error', response.message);
                                btn.html(btnName);
                                btn.removeAttr('disabled');
                            }
                        },
                        error: function(xhr, status, error) {
                            notify('error', error);
                            btn.removeAttr('disabled');
                            btn.html(btnName);
                        }
                    });
                });
            }

            //comment-edit-mode-hide-show//
            if (isLoggedIn) {
                $(document).on('click', '.editMode', function() {

                    $('.post-comment-text').removeClass('d-none');
                    $('.comment-list .commentEditForm').not($(this)).addClass('d-none');

                    var commentWrapper = $(this).closest('.comment-list');
                    var commentText = commentWrapper.find('.post-comment-text');
                    var editForm = commentWrapper.find('.commentEditForm');

                    commentText.toggleClass('d-none');
                    editForm.toggleClass('d-none');
                    commentWrapper.find('.edit-comment').val(commentText.text());
                });
            }

            $(document).on('click', '.loadMoreComment', function() {
                var btnAfterSubmit = `<div class="spinner-border"></div>`;
                var btnName = `@lang('More')...`;
                var btn = $(this);
                btn.html(btnAfterSubmit);
                btn.attr('disabled', true);

                //store
                var offset = $('.comment-list').length;
                var baseUrl = '{{ route('photo.more.comment', $gallery->id) }}';
                var url = baseUrl + '?offset=' + offset;

                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            btn.html(btnName);
                            btn.removeAttr('disabled');
                            if (offset >= response.length) {
                                btn.addClass('d-none');
                            }
                            $('.commentContainer').append(response.html);
                        } else {
                            notify('error', response.message);
                            btn.html(btnName);
                            btn.removeAttr('disabled');
                        }
                    },
                    error: function(xhr, status, error) {
                        notify('error', error);
                        btn.removeAttr('disabled');
                        btn.html(btnName);
                    }
                });

            });

            //alert-modal-hide-after-download
            $(".con-btn").on('click', function() {
                let modal = $("#confirmationAlert");
                modal.modal('hide');
            });

        });
    </script>

    <script src="{{ asset($activeTemplateTrue . 'js/central-gallery-react.js') }}"></script>
@endpush
