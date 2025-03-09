@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="complete-profile user-profile py-60">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-heading complete-profile-heading">
                        <h5 class="section-heading__title">@lang('Complete your profile, start exploring')</h5>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8 col-sm-10">
                    <form action="{{ route('user.data.submit') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="profile-item form-group">
                                    <div class="profile-item__content" id="profile-photo">
                                        <div class="edit-profile-image profile-item__value">
                                            <img class="profilePicPreview" src="{{ avatar(@$user->image ? getFilePath('userProfile') . '/' . $user->image : null) }}" alt="">
                                            <label class="cursor-pointer" for="upload-image">
                                                <input class="upload-profile-image absolute opacity-0" id="upload-image" name="image" type="file" accept="image/*">
                                                <span class="edit-image-icon"><i class="la la-camera"></i></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form--label" for="firstname"> @lang('First Name') </label>
                                    <input class="relative form--control" id="firstname" name="firstname" type="text" value="{{ old('firstname') }}" required>
                                </div>
                                <div class="form-group ">
                                    <label class="form--label" for="lastname"> @lang('Last Name') </label>
                                    <input class="relative form--control" id="lastname" name="lastname" type="text" value="{{ old('lastname') }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form--label" for="link">@lang('Profile Link')</label>
                                    <div class="input-group">
                                        <span class="input-group-text">{{ route('home') }}/</span>
                                        <input class="form-control form--control has-divider" id="link" name="profile_link" type="text" value="{{ $user->username }}" required>
                                    </div>
                                    <small class="link-show">
                                        <span class="profile-link"></span>
                                    </small>
                                    <small class="exist text--danger"></small>
                                </div>
                                <div class="form-group ">
                                    <label class="form--label" for="about"> @lang('About') </label>
                                    <textarea class="relative form--control nicEdit" id="about" name="about" placeholder="@lang('Hey 👋 I just created a page here. You can now buy me a coffee!')">{{ old('about', @$user->about) }}</textarea>
                                </div>
                                <div class="form-group ">
                                    <label class="form--label" for="web-url"> @lang('Website URL') <small>(@lang('Optional'))</small></label>
                                    <input class="relative form--control" id="web-url" name="website_link" type="url" value="{{ old('website_link', @$user->website_link) }}" placeholder="https://">
                                </div>
                                <div class="col-sm-12 form-group">
                                    <button class="btn btn--base w-100 page-button" type="submit">@lang('Continue')</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/nicEdit.js') }}"></script>
@endpush

@push('style')
    <style>
        .nicEdit-main {
            outline: none !important;
        }

        .nicEdit-custom-main {
            border-right-color: #cacaca73 !important;
            border-bottom-color: #cacaca73 !important;
            border-left-color: #cacaca73 !important;
            border-radius: 0 0 5px 5px !important;
        }

        .nicEdit-panelContain {
            border-color: #cacaca73 !important;
            border-radius: 5px 5px 0 0 !important;
            background-color: #fff !important
        }

        .nicEdit-buttonContain div {
            background-color: #fff !important;
            border: 0 !important;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            bkLib.onDomLoaded(function() {
                $(".nicEdit").each(function(index) {
                    $(this).attr("id", "nicEditor" + index);
                    new nicEditor({
                        fullPanel: true
                    }).panelInstance('nicEditor' + index, {
                        hasPanel: true
                    });
                    $('.nicEdit-main').parent('div').addClass('nicEdit-custom-main')
                });
            });

            $("#upload-image").on('change', function() {
                if (this.files && this.files[0]) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('.profilePicPreview').attr('src', e.target.result)
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });

            $('[name="profile_link"]').on('focusout', function(e) {
                var url = '{{ route('user.page.name.check') }}';
                var link = $(this).val();
                var data = {
                    link: link
                }

                $.get(url, data, function(response) {
                    var link = "{{ route('home') }}/" + response.link;
                    if (response.exist) {
                        $('.exist').removeClass('d-none');
                        $('.link-show').addClass('d-none');
                        $('.exist').text(link);
                        $('.page-button').prop('disabled', true);
                    } else {
                        if (!response.link) {
                            $('.exist').removeClass('d-none');
                            $('.link-show').addClass('d-none');
                            $('.exist').text(`@lang('Profile link is required!')`);
                            $('.page-button').prop('disabled', true);
                        } else {
                            $('.page-button').prop('disabled', false);
                            $('.exist').addClass('d-none');
                            $('.link-show').removeClass('d-none').addClass('text--success').text(link);
                        }
                    }
                });
            });

        })(jQuery);
    </script>
@endpush
