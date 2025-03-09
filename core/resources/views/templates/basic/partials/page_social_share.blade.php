<div class="share-card__share">
    <a class="page-share-btn btn btn--outline facebook"
        href="https://www.facebook.com/sharer/sharer.php?u={{ route('home.page', $user->profile_link) }}">
        <span class="icon"><i class="fab fa-facebook"></i></span> @lang('Facebook')
    </a>
    <a class="page-share-btn btn btn--outline linkedin"
        href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ route('home.page', $user->profile_link) }}&amp;title={{ @$user->profile_link }}&amp;summary={{ @$user->profile_link }}">
        <span class="icon"><i class="fab fa-linkedin-in"></i></span> @lang('Linkedin')
    </a>
</div>
<div class="share-card__share">
    <a class="page-share-btn btn btn--outline twitter"
        href="https://twitter.com/intent/tweet?text={{ __($user->profile_link) }}&amp;url={{ route('home.page', $user->profile_link) }}">
        <span class="icon"><i class="fab fa-twitter"></i></span> @lang('Tweet this')
    </a>
    <a class="page-share-btn btn btn--outline instagram" href="https://www.instagram.com/share?url={{ route('home.page', $user->profile_link) }}">
        <span class="icon"><i class="fab fa-instagram"></i></span> @lang('Instagram')
    </a>
</div>

<hr>
<h6>@lang('Share page link')</h6>
<div class="copy-link input-group mt-3">
    <span class="input-group-text fs-16"><i class="las la-link"></i></span>
    <input class="form-control form--control fs-14 ps-0" type="text" value="{{ route('home.page', $user->profile_link) }}" disabled>
    <span class="input-group-text flex-align copy-btn fs-14" id="copyBtn" data-link="{{ route('home.page', $user->profile_link) }}"><i
            class="far fa-copy"></i>&nbsp; @lang('Copy')</span>
</div>
@include($activeTemplate . 'partials.tips')
