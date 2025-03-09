<form action="{{ route('user.page.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="modal fade edit-profile-modal" id="editPage" style="display: none;">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('Edit Profile Page')</h4>
                    <div class="modal-action-btns">
                        <button class="btn btn--outline cancel-btn" data-bs-dismiss="modal" type="button" aria-label="Close">@lang('Cancel')</button>
                        <button class="btn btn--base" type="submit">@lang('Save')</button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="profile-item">
                        <div class="profile-item__content" id="profile-photo">
                            <div class="edit-profile-image profile-item__value">
                                <img class="profilePicPreview" src="{{ avatar(@$user->image ? getFilePath('userProfile') . '/' . $user->image : null) }}" alt="">
                                <label class="cursor-pointer" for="edit-image">
                                    <input id="edit-image" name="profile_image" type="file" accept="image/*" >
                                    <span class="edit-image-icon"><i class="la la-camera"></i></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="profile-item full-name">
                        <label class="profile-item__title">@lang('First Name')</label>
                        <div class="profile-item__content">
                            <input class="profile-item__value form--control" name="firstname" type="text" value="{{ @$user->firstname }}" required />
                        </div>
                    </div>
                    <div class="profile-item full-name">
                        <label class="profile-item__title">@lang('Last Name')</label>
                        <div class="profile-item__content">
                            <input class="profile-item__value form--control" name="lastname" type="text" value="{{ @$user->lastname }}" required />
                        </div>
                    </div>
                    <div class="profile-item creating">
                        <label class="profile-item__title">@lang('What are you creating?')
                        </label>
                        <div class="profile-item__content">
                            <input class="profile-item__value form--control" name="creation" type="text" value="{{ old('creation', @$user->creation) }}" placeholder="@lang('Creating music,coronrelife,code etc')..." required />
                        </div>
                    </div>
                    <div class="profile-item about-me">
                        <label class="profile-item__title">@lang('About')</label>
                        <div class="profile-item__content nicedit-textarea">
                            <textarea class="profile-item__value form--control nicEdit" name="about" placeholder="@lang('About youself')...">@php echo @$user->about;@endphp</textarea>
                        </div>
                    </div>

                    <div class="profile-item socila-link">
                        <label class="profile-item__title">@lang('Social links') <small>(@lang('Optional'))</small></label>
                        <div class="profile-item__content">
                            <div class="socialLinkContainer">
                                @if (@!$user->social_links)
                                    <div class="input-group custom-input-group mb-3">
                                        <span class="input-group-text" id="basic-addon1">#</span>
                                        <input name="icon[]" type="hidden">
                                        <input class="profile-item__value form--control form-control" name="link[]" type="text" placeholder="@lang('Paste your social connection link')..." />
                                        <span class="clear-all-btn cursor-pointer"><i class="fas fa-times"></i></span>
                                    </div>
                                @endif
                            </div>
                            <button class="btn btn--outline addNewLink" type="button">
                                <span><i class="fa fa-plus"></i> @lang(' Add Social Link')</span>

                            </button>
                        </div>
                    </div>

                    <div class="profile-item flex-between">
                        <label class="profile-item__title">@lang('Replace “coffee” with anything you like')</label>
                        <div class="profile-item__content">
                            <div class="d-flex">
                                <input class="emoji-select form--control" name="donate_emoji" type="text" value="{{ @$user->donate_emoji ?? $general->emoji }}" placeholder="☕" required>
                                <input class="form--control" name="donate_emoji_name" type="text" value="{{ @$user->donate_emoji_name ?? $general->emoji_name }}" placeholder="@lang('Coffee,Pizza,Beer,Tea etc')..." required>
                            </div>
                        </div>
                    </div>

                    <div class="profile-item">
                        <label class="profile-item__title">@lang('Theme color')<span class="tooltiop fs-12"><i
                                   class="far fa-question-circle"></i></span></label>
                        <div class="profile-item__content ">
                            <input class="fomr--control" name="theme_color" type="hidden" value="#ff813f">
                            <input class="fomr--control" name="theme_color_name" type="hidden" value="Pmupkin Spice">
                            <div class="dropdown">
                                <button class="dropdown-toggle color-selection" data-bs-toggle="dropdown" type="button" aria-expanded="false">
                                    <span class="seleted-color" id="edit-selected-color"></span>
                                    <span id="edit-selected-name">@lang('Pmupkin Spice')</span>
                                </button>
                                <ul class="dropdown-menu color-list">
                                    <li class="single-color-list"><span class="option-color" data-bg-color="#ff813f" data-color-name="Pmupkin Spice"></span>@lang('Pmupkin Spice')</li>
                                    <li class="single-color-list"><span class="option-color" data-bg-color="#5f7fff" data-color-name="Serene Blue"></span>@lang('Serene Blue')</li>
                                    <li class="single-color-list"><span class="option-color seleted-color" data-bg-color="#bd5fff" data-color-name="Plum Passion"></span>@lang('Plum Passion')</li>
                                    <li class="single-color-list"><span class="option-color" data-bg-color="#ff5f5f" data-color-name="Crimson Sunset"></span>@lang('Crimson Sunset')</li>
                                    <li class="single-color-list"><span class="option-color" data-bg-color="#26b0a1" data-color-name="Teal Dream"></span>@lang('Teal Dream')</li>
                                    <li class="custom-color single-color-list"><span class="custom-color__icon option-color" data-color-name="Custom">
                                            <img src="{{ getImage($activeTemplateTrue . 'images/icons/multi-color.png') }}" alt=""></span>@lang('Custom Color')
                                    </li>
                                </ul>
                                <input class="fomr--control" id="customColor-input" name="custom_color" type="hidden" value="#336699">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('style-lib')
    <link href="{{ asset('assets/global/css/emojionearea.min.css') }}" rel="stylesheet" />
@endpush
@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/nicEdit.js') }}"></script>
    <script src="{{ asset('assets/global/js/emojionearea.min.js') }}"></script>
@endpush

@push('style')
    <style>
        .nicEdit-main {
            outline: none !important;
            width: 100% !important;
        }

        .nicEdit-custom-main {
            border-right-color: #cacaca73 !important;
            border-bottom-color: #cacaca73 !important;
            border-left-color: #cacaca73 !important;
            border-radius: 0 0 5px 5px !important;
            width: 100% !important;
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

        .nicedit-textarea>div {
            width: 100% !important;
        }

        .profile-item__content .emojionearea.form--control {
            height: 45px !important;
        }

        .emojionearea.emojionearea-inline>.emojionearea-editor {
            top: 7px !important;
        }

        @media screen and (max-width: 575px) {
            .modal-title {
                margin-bottom: 15px;
            }
        }

        .emojionearea.emojionearea-inline>.emojionearea-button {
            top: unset !important;
        }
    </style>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {

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
            //em

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

            $(".editPage").on('click', function() {
                let modal = $('#editPage');
                modal.modal('show');

                var socialLinksData = <?php echo json_encode(@$user->social_links); ?>;
                if (socialLinksData) {
                    socialLinksData.forEach(function(linkData) {
                        const parsedUrl = new URL(linkData.link);
                        const domain = parsedUrl.hostname.replace(/^www\./, '');
                        const domainParts = domain.split('.');
                        const mainPart = domainParts[0];
                        const iconHtml = `<i class="la las la-${mainPart}"></i>`;

                        const html = `<div class="input-group custom-input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">${iconHtml}</span>
                        <input class="profile-item__value form--control form-control" name="link[]" type="text" placeholder="@lang('Paste your social link here.')" value="${linkData.link}" />
                        <span class="clear-all-btn cursor-pointer"><i class="fas fa-times"></i></span>
                    </div>`;

                        $(".socialLinkContainer").append(html);
                    });
                }


            });


            $(document).on('change', "#edit-image", function() {
                if (this.files && this.files[0]) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('.profilePicPreview').attr('src', e.target.result)
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            })


            //social-part-start//
            $(document).on('click', '.addNewLink', function() {
                var html = `<div class="input-group custom-input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">#</span>
                            <input name="icon[]" type="hidden" class="icon-field" >
                            <input class="profile-item__value form--control form-control" name="link[]" type="text" placeholder="@lang('Paste your social link here.')" />
                            <span class="clear-all-btn cursor-pointer"><i class="fas fa-times"></i></span>
                            </div>`;
                $(".socialLinkContainer").append(html);
            });



            $(document).on('input', '.profile-item__value', function() {
                let url = $(this).val();
                if (!url.includes("https://") && url.includes("www.")) {
                    url = `https://${url}`;
                }
                if (!url.includes("www.") && !url.includes("https://")) {
                    url = `https://www.${url}`;
                }
                try {
                    const parsedUrl = new URL(url);
                    if (parsedUrl) {
                        let domain = parsedUrl.hostname.replace(/^www\./, '').split('.');
                        $(this).parent().find('.input-group-text').html(`<i class="la las la-${domain[0]}"></i>`);
                    }
                } catch (e) {
                    console.log("Invalid Icon");
                }
            })


            $(document).on('click', '.clear-all-btn', function() {
                $(this).closest('.custom-input-group').remove();
            });
            //social-part-end//

            //Start theme-color
            function updateSelectedColorAndName(colorCode, colorName) {
                $("#edit-selected-color").css("background-color", colorCode);
                $("#edit-selected-name").text(colorName);
            }

            $('.single-color-list').on('click', function() {
                var colorCode = $(this).find('.option-color').data('bg-color');
                var colorName = $(this).find('.option-color').data('color-name');
                $("[name='theme_color']").val(colorCode);
                $("[name='theme_color_name']").val(colorName);
                updateSelectedColorAndName(colorCode, colorName);
            });

            var initialColorCode = "{{ @$user->theme_color }}";
            var initialColorName = "{{ @$user->theme_color_name }}";

            @if (isset($user->theme_color_name))
                initialColorName = "{{ $user->theme_color_name }}";
            @else
                initialColorName = "@lang('Pmupkin Spice')";
            @endif
            updateSelectedColorAndName(initialColorCode, initialColorName);
            //End theme-color





        })(jQuery)
    </script>
@endpush
