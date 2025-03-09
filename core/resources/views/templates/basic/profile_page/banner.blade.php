@php
    $defaultCoverContent = getContent('default_profile_cover.content', true);
    $defaultCover = getImage('assets/images/frontend/default_profile_cover/' . @$defaultCoverContent->data_values->image, '1830x400');
    $authUser = auth()->user();
@endphp
<div class="row text-center">
    <form id="profile-cover-image">
        <div class="col-12">
            <div class="profile-cover-img @if ($user->id != $authUser?->id) follow @endif bg-img" data-background-image="{{ @$user->cover_image ? getImage(getFilePath('profileCover') . '/' . @$user->cover_image) : $defaultCover }}">
                @if ($user->id == $authUser?->id)
                    <div class="cover-img-btns">
                        <div class="cover-photo-action-wrapper d-flex flex-column align-items-end justify-content-end">
                            <label class="btn btn--base" for="change-profile-cover" title="@lang('Resized into') {{ getFileSize('profileCover') }}px">
                                <input class="custom-file-input" id="change-profile-cover" name="cover_image" type="file">
                                <i class="fa fa-camera-retro"></i>
                                @if (@$user->cover_image)
                                    @lang('Replace image')
                                @else
                                    @lang('Add cover image')
                                @endif
                            </label>
                            <span class="text-white image-instruction w-100">
                                @lang('Please ensure image size ') <b>{{ getFileSize('profileCover') }}</b> @lang('px')</b>
                            </span>
                        </div>
                    </div>
                @else
                    <div class="cover-img-btns follow">
                        <button class="btn follow-button--sm btn--outline follow-button" data-user_id= "{{ @$user->id }}" type="button">
                            @if (@$isFollowing)
                                <span class="icon"><i class="las la-check"></i></span> <span class="follow-text">@lang('Following')</span>
                            @else
                                <span class="icon"><i class="las la-plus"></i></span> <span class="follow-text">@lang('Follow')</span>
                            @endif
                        </button>
                    </div>
                @endif
            </div>
            @if ($user->id == $authUser?->id)
                <div class="profile-img">
                    <label class="cursor-pointer w-100 h-100 rounded-circle" for="edit-img">
                        <img class="h-100 bg-white page-image-preview" src="{{ getImage(getFilePath('userProfile') . '/' . @$user->image, getFileSize('userProfile')) }}" alt="{{ $user->fullname }}">
                        <input class="absolute opacity-0 profileImage" id="edit-img" name="profile_image" type="file" accept="image/*">
                    </label>
                </div>
            @else
                <div class="profile-img">
                    <label class="w-100 h-100 rounded-circle">
                        <img class="h-100 bg-white" src="{{ getImage(getFilePath('userProfile') . '/' . @$user->image, getFileSize('userProfile')) }}" alt="{{ $user->fullname }}">
                    </label>
                </div>
            @endif
        </div>

    </form>
</div>

<div class="modal fade confirm-modal custom--modal" id="confirm-upload-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h4 class="modal-title">@lang('Confirmation Alert!')</h4>
                <p class="modal-desc mt-3 question"></p>
                <button class="btn btn--base w-100 mt-5 profileImageChange" type="button">@lang('Yes')</button>
                <button class="btn btn-outline--light w-100 mt-3 reloadBtn" data-bs-dismiss="modal" type="button">@lang('No')</button>
            </div>
        </div>
    </div>
</div>


@push('script')
    <script>
        "use strict";
        (function($) {
            $(document).on('change', ".custom-file-input", function() {
                if (this.files && this.files[0]) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $(".profile-cover-img")
                            .addClass('bg-img')
                            .css("background-image", "url(" + e.target.result + ")");
                    }
                    reader.readAsDataURL(this.files[0]);
                };

                var modal = $('#confirm-upload-modal');
                modal.find(".question").text(`@lang('Are you sure to change cover image?')`);
                modal.modal('show');

            });

            $(document).on('change', ".profileImage", function() {
                if (this.files && this.files[0]) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('.page-image-preview').attr('src', e.target.result).addClass('h-100 bg-white')
                    }
                    reader.readAsDataURL(this.files[0]);
                };

                var modal = $('#confirm-upload-modal');
                modal.find(".question").text(`@lang('Are you sure to change profile image?')`);
                modal.modal('show');
            });

            $(".profileImageChange").on('click', function() {

                var formData = new FormData($('#profile-cover-image')[0]);
                var url = '{{ route('user.profile.cover.image') }}';
                var token = '{{ csrf_token() }}';
                formData.append('_token', token);

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (!response.success) {
                            notify('error', response.message);
                        };
                        var modal = $('#confirm-upload-modal');
                        modal.modal('hide');
                    },
                    error: function(xhr, status, error) {
                        notify('error', error);
                    }
                });
            })
            $(".reloadBtn").on('click', function(e) {
                window.location.reload();
            });


            //follow-unfollow//
            $(document).on('click', '.follow-button', function(e) {
                e.preventDefault();
                var isAuthenticated = @json(auth()->check());
                if (!isAuthenticated) {
                    notify('error', 'Sign in required for following');
                    return;
                }
                var $this = $(this);
                var $followerCount = $('.totalFollower');
                var userId = `{{ $user->id }}`;
                var followerId = `{{ @$authUser->id }}`;
                $.ajax({
                    type: "POST",
                    url: "{{ route('user.explore.toggle.follow') }}",
                    data: {
                        user_id: userId,
                        follower_id: followerId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (!response.success) {
                            notify('error', response.message);
                            return;
                        }
                        notify('success', response.message);
                        if (response.action === 'follow') {
                            $this.html('<i class="las la-user-check"></i> <span class="follow-text">@lang('Unfollow')</span>');
                            $followerCount.text(incrementCount($followerCount.text()));
                        } else {
                            $this.html('<i class="las la-user-plus"></i> <span class="follow-text">@lang('Follow')</span>');
                            $followerCount.text(decrementCount($followerCount.text()));
                        }
                    }
                });
            });

            function incrementCount(count) {
                var numericCount = expandShortNumber(count);
                numericCount++;
                return formatShortNumber(numericCount);
            }

            function decrementCount(count) {
                var numericCount = expandShortNumber(count);
                numericCount = Math.max(0, numericCount - 1);
                return formatShortNumber(numericCount);
            }

            function expandShortNumber(shortNumber) {
                var parts = shortNumber.match(/(\d+(\.\d+)?)([kKmM]?)/);
                var number = parseFloat(parts[1]);
                var multiplier = parts[3].toLowerCase();

                switch (multiplier) {
                    case 'k':
                        return number * 1000;
                    case 'm':
                        return number * 1000000;
                    default:
                        return number;
                }
            }

            function formatShortNumber(number) {
                if (number < 1000) return number.toString();
                if (number < 1000000) return (number / 1000).toFixed(1).replace(/\.0$/, '') + 'K';
                return (number / 1000000).toFixed(1).replace(/\.0$/, '') + 'M';
            }


            $('.follow-button').hover(
                function() { // Mouse enter
                    var $followText = $(this).find('.follow-text');
                    if ($followText.text() === '@lang('Following')') {
                        $followText.text('@lang('Unfollow')');
                    }
                },
                function() { // Mouse leave
                    var $followText = $(this).find('.follow-text');
                    if ($followText.text() === '@lang('Unfollow')') {
                        $followText.text('@lang('Following')');
                    }
                }
            );
            //end follow and unfollow//

        })(jQuery);
    </script>
@endpush
