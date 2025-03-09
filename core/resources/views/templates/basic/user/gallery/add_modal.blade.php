<form id="galleryForm" action="">
    <div class="modal fade add-img-modal custom--modal" id="add-img-modal" aria-labelledby="addimg-modal" aria-hidden="true" tabindex="-1" style="display: none;">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <h4 class="modal-title">@lang('Add Gallery Photo')</h4>
                    <button data-bs-dismiss="modal" type="button" aria-label="Close">
                        <span class="icon fs-20"><i class="fa fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="gallery-modal-item  gallery-img mb-3">
                        <img class="profilePicPreview" src="{{ photo(null) }}" title="@lang('Image resized into') {{ getFileSize('gallery') }}@lang('px')" alt="gallery-img">
                        <label for="add-gallery-img">
                            <input id="add-gallery-img" name="image" type="file" required accept="image/*">
                            <span class="icon flex-center"><i class="las la-camera"></i></span>
                        </label>
                    </div>
                    <small class="image-instruction text-center d-block mb-3">@lang('Ensure the size into') {{ getFileSize('gallery') }}@lang('px')</small>

                    <div class="gallery-modal-item">
                        <div class="form-group">
                            <input class="form--control" name="title" type="text" placeholder="@lang('Photo title')" required>
                        </div>
                        <div class="form-group">
                            <textarea class="form--control" name="content" placeholder="@lang('Description (Optional)')"></textarea>
                        </div>
                        <div class="form-group">
                            <select class="select form-select" name="status" required>
                                <option value="1" selected>@lang('Publish Now')</option>
                                <option value="0">@lang('Save as Draft')</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form--label">@lang('Who can see this photo?')</label>
                            <select class="select form-select" name="visible" required>
                                <option value="1">@lang('Public')</option>
                                <option value="2">@lang('Supports Only')</option>
                                <option value="3">@lang('Members Only')</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="privet-message form--check" for="notify-followers">
                                <span class="custom--check">
                                    <input class="form-check-input" id="notify-followers" name="notify_followers" type="checkbox">
                                </span>
                                <p class="form-check-label text--base"><small>@lang('Yes! I Confirm to notify my followers about this photo?')</small></p>
                            </label>
                        </div>
                    </div>
                    <div class="gallery-modal-item submit">
                        <button class="btn btn--base w-100 publish-gallery-btn" type="submit">@lang('Publish Now')</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('style')
    <style>
        .spinner-border {
            width: 1rem;
            height: 1rem;
        }
    </style>
@endpush

@push('script')
    <script>
        "use strict";

        $(document).ready(function() {
            $("#add-gallery-img").on('change', function() {
                if (this.files && this.files[0]) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('.profilePicPreview').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });

            $("[name=status]").on('change', function() {
                let status = $(this).val();
                if (status == 1) {
                    $('.publish-gallery-btn').text(`@lang('Publish Now')`);
                } else {
                    $('.publish-gallery-btn').text(`@lang('Save as Draft')`);
                }
            });

            $('#galleryForm').on('submit', function(e) {
                e.preventDefault();
                var btnAfterSubmit = `<div class="spinner-border"></div>`;
                var btn = $('.publish-gallery-btn');
                btn.html(btnAfterSubmit);
                btn.attr('disabled', true);

                var formData = new FormData($('#galleryForm')[0]);
                var url = '{{ route('user.gallery.store') }}';
                var token = '{{ csrf_token() }}';
                formData.append('_token', token);

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#add-img-modal').modal('hide');
                            window.location.href = response.redirect_url;
                            notify('success', `@lang('Gallery photo added successfully')`);
                        } else {
                            notify('error', response.message);
                        }
                        if (response.is_publish) {
                            btn.html(`@lang('Publish Now')`);
                        } else {
                            btn.html(`@lang('Save as Draft')`);
                        }
                        btn.removeAttr('disabled');
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
