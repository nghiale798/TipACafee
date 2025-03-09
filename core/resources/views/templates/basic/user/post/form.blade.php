@extends($activeTemplate . 'layouts.app')
@section('panel')
    <div class="create-post-page pt-44">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <a class="btn back-btn btn-outline--light" href="{{ route('user.post.index') }}">
                        <i class="fas fa-arrow-left"></i>
                        @lang('Back to Post')
                    </a>
                </div>
            </div>
            <div class="new-post-form">
                <form id="postForm">
                    <div class="row gy-4">
                        <div class="col-lg-9 col-md-7">
                            <div class="form-main-aria">
                                <input class="post-title" name="title" type="text" value="{{ old('title', @$post->title) }}" placeholder="@lang('Title')">
                                <div class="texteditor">
                                    <div class="texteditor__header">
                                        <label class="form-label">@lang('Post Content')</label>
                                    </div>
                                    <textarea class="form--control nicEdit" name="content" rows='22' placeholder="@lang('Write something')...">{{ old('content', @$post->content) }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-5">
                            <aside class="sidebar-widgets">
                                <div class="publish-wrapper justify-content-between">
                                    <button class="btn publish-post-btn btn-outline--light" type="submit" disabled> @lang('Publish Now')</button>
                                    <div class="dropdown-center">
                                        <button class="btn publish-time-btn dropdown-toggle " data-bs-toggle="dropdown" type="button" aria-expanded="false"></button>
                                        <input name="status" type="hidden" value="1">
                                        <ul class="dropdown-menu publish-time-list dropdown-menu-end">
                                            <li class="item post-status publish" data-status="{{ Status::PUBLISH }}"> @lang('Publish Now')
                                            </li>
                                            <li class="item post-status draft" data-status="{{ Status::DRAFT }}">@lang('Save as Draft')
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="widget-card">
                                    <div class="post-type">
                                        <h6 class="widget-card__title">@lang('Who can see this post?')</h6>
                                        <div class="post-type-selected">
                                            <div class="selected-text">
                                                <span class="icon"><i class="fas fa-globe-asia"></i></span>
                                                <p class="text">@lang('Public')</p>
                                            </div>
                                            <span class="icon seleted"><i class="fas fa-check"></i></span>
                                        </div>
                                        <ul class="post-type-select d-none">
                                            <input name="visible" type="hidden" value="1">
                                            <li class="post-visible" data-visible="1">
                                                <div class="select-text">
                                                    <div class="d-flex gap-3">
                                                        <span class="icon"><i class="fas fa-globe-asia"></i></span>
                                                        <p class="text">@lang('Public')</p>
                                                    </div>
                                                    <span class="select-icon is-public"><i class="fa fa-check"></i></span>
                                                </div>
                                                <p class="post-type-select__desc">
                                                    @lang('Visible to all your followers and the public.')
                                                </p>
                                            </li>
                                            <li class="post-visible" data-visible="2">
                                                <div class="select-text">
                                                    <div class="d-flex gap-3">
                                                        <span class="icon"><i class="fa fa-heart"></i></span>
                                                        <p class="text">@lang('Supporters Only')</p>
                                                    </div>
                                                    <span class="select-icon is-supporter"><i class="fa fa-check"></i></span>
                                                </div>
                                                <p class="post-type-select__desc">
                                                    @lang('Visible to all your supporters and members only.')
                                                </p>
                                            </li>

                                            <li class="post-visible" data-visible="3">
                                                <div class="select-text">
                                                    <div class="d-flex gap-3">
                                                        <span class="icon"><i class="fa fa-lock"></i></span>
                                                        <p class="text">@lang('Members Only')</p>
                                                    </div>
                                                    <span class="select-icon is-member"><i class="fa fa-check"></i></span>
                                                </div>
                                                <p class="post-type-select__desc">
                                                    @lang('Visible to all your members only.')
                                                </p>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="post-category">
                                        <div class="post-category__header">
                                            <div class="form-group flex-grow-1">
                                                <label class="widget-card__title">@lang('Category') <small>(@lang('Optional'))</small></label>
                                                <select class="form--control" name="category_id">
                                                    <option value="" selected>@lang('Select One')</option>
                                                    @foreach ($categories as $key => $category)
                                                        <option value="{{ $category->id }}" @selected($category->id == @$post->category_id)>
                                                            {{ __($category->name) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="no-post-category">
                                            <span class="no-post-category__icon"> <i class="las la-network-wired"></i></span>
                                            <p class="no-post-category__desc m-auto">
                                                @lang('Category makes it easy to browse your posts.')
                                            </p>
                                        </div>
                                    </div>

                                    <div class="post-category">
                                        <div class="form-group">
                                            <label class="privet-message form--check" for="notify-followers">
                                                <span class="custom--check">
                                                    <input class="form-check-input" id="notify-followers" name="notify_followers" type="checkbox">
                                                </span>
                                                <p class="form-check-label text--base"><small>  @lang('Yes! I Confirm notify my followers to about this post?')</small></p>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </aside>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Share modal   --}}
    <div class="modal fade custom--modal" id="postShareModal" style="display: none;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <button class="btn-close close-preview flex-center" data-bs-dismiss="modal" type="button" aria-label="Close"> <i
                       class="fas fa-times"></i></button>
                <div class="modal-body text-center">
                    <span class="share-icon"><i class="las la-rocket"></i></span>
                    <h5 class="modal-title py-2" id="newPostModalLabel">@lang('Your post is live')!</h5>
                    <p>@lang('Share this post wherever your followers are. Posts are the best way to make new supporters.')</p>
                    <h6 class="py-3">@lang('Tell your followers everywhere')🎉</h6>
                    <div class="modal-body__content share-action">
                        <div class="copy-link input-group mt-4">
                            <span class="input-group-text fs-16"><i class="las la-link"></i></span>
                            <input class="form-control form--control fs-14 ps-0" type="text" value="@if (@$post) {{ route('post.view', [@$user->profile_link, @$post->slug]) }} @endif" disabled>
                            <span class="input-group-text flex-align copy-btn fs-14 cursor-pointer" id="copyBtn" data-link="@if (@$post) {{ route('post.view', [@$user->profile_link, @$post->slug]) }} @endif"><i
                                   class="far fa-copy"></i>&nbsp; @lang('Copy')</span>
                        </div>
                        @include($activeTemplate . 'partials.tips')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/nicEdit.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/jquery.validate.js') }}"></script>
@endpush

@push('style')
    <style>
        .nicEdit-main {
            outline: none !important;
        }

        .nicEdit-custom-main {
            border-right-color: hsl(var(--border-color)) !important;
            border-bottom-color: hsl(var(--border-color)) !important;
            border-top-color: hsl(var(--border-color)) !important;
            border-left-color: hsl(var(--border-color)) !important;
            border-radius: 0 0 5px 5px !important;
            color: hsl(var(--black)/.9)
        }

        .nicEdit-panelContain {
            border-color: hsl(var(--border-color)) !important;
            border-radius: 5px 5px 0 0 !important;
            background-color: #fff !important
        }

        .nicEdit-buttonContain div {
            background-color: #fff !important;
            border: 0 !important;
        }

        .share-icon {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            background-color: hsl(var(--base));
            font-size: 32px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            color: #fff;
        }

        .spinner-border {
            width: 1rem;
            height: 1rem;
        }

        .post-category__header .form--control {
            background-color: #fff !important;
        }
    </style>
@endpush

@push('script')
    <script>
        "use strict";
        $(document).ready(function() {

            bkLib.onDomLoaded(function() {
                $(".nicEdit").each(function(index) {
                    $(this).attr("id", "nicEditor" + index);
                    new nicEditor({
                        fullPanel: true
                    }).panelInstance('nicEditor' + index, {
                        hasPanel: true
                    });
                    $('.nicEdit-main').parent('div').addClass('nicEdit-custom-main');

                    //out-of-nicEdit-config//
                    $('.nicEdit-main, [name=title]').on('input', function() {
                        var hasContent = $('.nicEdit-main').text().trim().length > 0;
                        var hasTitle = $("[name=title]").val().trim().length > 0;
                        var isDisabled = (hasContent && hasTitle);

                        $(".publish-post-btn")
                            .toggleClass('btn-outline--light', !hasContent)
                            .toggleClass('btn--base text-black', hasContent)
                            .prop('disabled', isDisabled);
                    });
                });
            });

            @if (@$post)
                $('.publish-post-btn').prop('disabled', false).toggleClass('btn--base text-black').removeClass('btn-outline--light');
            @endif

            @if (@$post)
                @if (@$post->status == Status::DRAFT)
                    $('.publish-post-btn').text(`@lang('Save as Draft')`);
                    $("[name=status]").val(0);
                @elseif (@$post->status == Status::PUBLISH)
                    $('.publish-post-btn').text(`@lang('Update Now')`);
                    $("[name=status]").val(1);
                @endif
            @else
                $('.publish-post-btn').text(`@lang('Publish Now')`);
                $("[name=status]").val(1);
            @endif

            $('.post-status').on('click', function() {
                let status = $(this).data('status');
                if (status == 1) {
                    $('.publish-post-btn').text(`@lang('Publish Now')`);
                    $("[name=status]").val(1);
                } else {
                    $("[name=status]").val(0);
                    $('.publish-post-btn').text(`@lang('Save as Draft')`);
                }
            });

            @if (@$post && @$post->visible == Status::VISIBLE_PUBLIC)
                var supporterHtml = `<span class="icon"><i class="fas fa-heart"></i></span>
                                        <p class="text">@lang('Public')</p>`;
                $('.selected-text').html(supporterHtml);
                $('.is-public').hide();
                $('.is-supporter').show();
                $("[name=visible]").val(1);
            @elseif (@$post->visible == Status::VISIBLE_SUPPORTER)
                var supporterHtml = `<span class="icon"><i class="fas fa-heart"></i></span>
                                        <p class="text">@lang('Supporters Only')</p>`;
                $('.selected-text').html(supporterHtml);
                $('.is-public').hide();
                $('.is-supporter').show();
                $("[name=visible]").val(2);
            @else
                var supporterHtml = `<span class="icon"><i class="fas fa-lock"></i></span>
                                        <p class="text">@lang('Members Only')</p>`;
                $('.selected-text').html(supporterHtml);
                $('.is-public').hide();
                $('.is-supporter').show();
                $("[name=visible]").val(3);
            @endif

            $('.is-supporter').hide();
            $('.post-visible').on('click', function() {
                let visible = $(this).data('visible');
                if (visible == 1) {
                    var publicHtml = `<span class="icon"><i class="fas fa-globe-asia"></i></span>
                            <p class="text">@lang('Public')</p>`;
                    $('.selected-text').html(publicHtml);
                    $('.is-supporter').hide();
                    $('.is-member').hide();
                    $('.is-public').show();
                    $("[name=visible]").val(1);
                } else if (visible == 2) {
                    var supporterHtml = `<span class="icon"><i class="fas fa-heart"></i></span>
                            <p class="text">@lang('Supporters Only')</p>`;
                    $('.selected-text').html(supporterHtml);
                    $('.is-public').hide();
                    $('.is-member').hide();
                    $('.is-supporter').show();
                    $("[name=visible]").val(2);
                } else {
                    var supporterHtml = `<span class="icon"><i class="fas fa-lock"></i></span>
                            <p class="text">@lang('Members Only')</p>`;
                    $('.selected-text').html(supporterHtml);
                    $('.is-public').hide();
                    $('.is-supporter').hide();
                    $('.is-member').show();
                    $("[name=visible]").val(3);
                }
            });



            //Ajax
            $('#postForm').on('submit', function(e) {
                e.preventDefault();


                var btnAfterSubmit = `<div class="spinner-border"></div>`;
                var btn = $('.publish-post-btn');
                btn.html(btnAfterSubmit);
                btn.attr('disabled', true);

                //store
                var formData = new FormData($('#postForm')[0]);
                var nicInstance = nicEditors.findEditor('nicEditor0');
                var nicContent = nicInstance.getContent();

                var url = '{{ route('user.post.store', @$post->id ?? '') }}';
                var token = '{{ csrf_token() }}';

                formData.append('_token', token);
                formData.append('content', nicContent);

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    complete: function(data) {
                        btn.removeAttr('disabled');
                    },
                    success: function(response) {
                        if (response.success) {
                            notify('success', response.message);
                            @if (!@$post)
                                window.location.href = response.redirect_url
                            @else
                                if (response.is_publish) {
                                    $('#postShareModal').modal('show');

                                    $('.publish-time-list .publish').addClass('d-none')
                                    $('.publish-time-list .draft').removeClass('d-none')

                                } else {
                                    $('.publish-time-list .draft').removeClass('d-none')
                                    $('.publish-time-list .publish').addClass('d-none')
                                    @if (@$post)
                                        $('.publish-time-list .draft').addClass('d-none')
                                        $('.publish-time-list .publish').removeClass('d-none')
                                    @endif
                                }
                            @endif

                        } else {
                            notify('error', response.message);
                        }

                        if (response.is_publish) {
                            btn.html(`@lang('Publish Now')`);
                            @if (@$post)
                                btn.html(`@lang('Update Now')`);
                            @endif
                        } else {
                            btn.html(`@lang('Save as Draft')`);
                        }
                    },
                    error: function(xhr, status, error) {
                        notify('error', error);
                        btn.removeAttr('disabled');
                    }
                });

            });

        });
    </script>
@endpush
