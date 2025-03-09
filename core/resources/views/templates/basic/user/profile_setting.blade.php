@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="multi-page-card">
        <div class="multi-page-card__body">
            <div class="row">
                <div class="col-12">
                    <div class="section-heading complete-profile-heading">
                        <h4 class="section-heading__title">{{ __($pageTitle) }}</h4>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-12">
                    <form class="register" action="" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="profile-item form-group">
                                <div class="profile-item__content" id="profile-photo">
                                    <div class="edit-profile-image profile-item__value">
                                        <img class="profilePicPreview" src="{{ avatar(@$user->image ? getFilePath('userProfile') . '/' . $user->image : null) }}" alt="">
                                        <label class="cursor-pointer" for="edit-img">
                                            <input class="upload-profile-image absolute opacity-0" id="edit-img" name="image" type="file" accept="image/*">
                                            <span class="edit-image-icon"><i class="la la-camera"></i></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('First Name')</label>
                                <input class="form-control form--control" name="firstname" type="text" value="{{ $user->firstname }}" required>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('Last Name')</label>
                                <input class="form-control form--control" name="lastname" type="text" value="{{ $user->lastname }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('E-mail Address')</label>
                                <input class="form-control form--control" value="{{ $user->email }}" readonly>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('Mobile Number')</label>
                                <input class="form-control form--control" value="{{ $user->mobile }}" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('Address')</label>
                                <input class="form-control form--control" name="address" type="text" value="{{ @$user->address->address }}">
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('State')</label>
                                <input class="form-control form--control" name="state" type="text" value="{{ @$user->address->state }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('Zip Code')</label>
                                <input class="form-control form--control" name="zip" type="text" value="{{ @$user->address->zip }}">
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('City')</label>
                                <input class="form-control form--control" name="city" type="text" value="{{ @$user->address->city }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label">@lang('Country')</label>
                                <input class="form-control form--control" value="{{ @$user->address->country }}" readonly>
                            </div>

                        </div>

                        <div class="form-group">
                            <button class="btn btn--base w-100" type="submit">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        "use strict";
        (function($) {
            $("#upload-image").on('change', function() {
                if (this.files && this.files[0]) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('.profilePicPreview').attr('src', e.target.result)
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
        })(jQuery);
    </script>
@endpush
