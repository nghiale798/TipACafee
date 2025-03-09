<div class="modal fade " id="thankYouModal">
    <div class="modal-dialog modal-dialog-centered preview-modal">
        <div class="modal-content">
            <button class="btn-close close-preview flex-center" data-bs-dismiss="modal" type="button" aria-label="Close"> <i class="fas fa-times"></i></button>
            <div class="modal-body text-center">
                <div class="modal-body__img">
                    <img src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, getFileSize('userProfile')) }}" alt="">
                </div>
                <div class="modal-body__content">
                    <h5 class="modal-body__title">@lang('Thank you for your support')</h5>
                    <p class="modal-body__desc">{{ __($message) }}</p>
                    <div class="modal-body__share">
                        <p class="share-card__title">
                            <span class="icon"> {{ $user->donate_emoji }}</span>{{ __('You are donate to' . ' ' . $user->fullname . ' ' . $user->donate_emoji_name) }}.
                        </p>
                        <div class="modal-body__content share-action">
                            <div class="copy-link input-group mt-4">
                                <span class="input-group-text fs-16"><i class="las la-link"></i></span>
                                <input class="form-control form--control fs-14 ps-0" type="text" value="{{ route('home.page', $user->profile_link) }}" disabled>
                                <span class="input-group-text flex-align copy-btn fs-14 cursor-pointer" id="copyBtn" data-link="{{ route('home.page', $user->profile_link) }}"><i class="far fa-copy"></i>&nbsp; @lang('Copy')</span>
                            </div>
                            @include($activeTemplate . 'partials.tips')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
