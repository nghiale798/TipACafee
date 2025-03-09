{{-- <p class="fs-14">{{ __($level->description) }}</p>
<ul class="reward-card__list">
    <li class="preview-card__list-items">
        <span class="icon"><i class="las la-calendar-alt"></i></span>
        <p class="text one">{{ __($level->rewards->one) }}</p>
    </li>
    <li class="preview-card__list-items">
        <span class="icon"><i class="las la-book-reader"></i></span>
        <p class="text two">{{ __($level->rewards->two) }}</p>
    </li>
    <li class="preview-card__list-items">
        <span class="icon"><i class="las la-image"></i></span>
        <p class="text three">{{ __($level->rewards->three) }}</p>
    </li>
</ul> --}}

<p class="fs-14 py-3">{{ __($level->description) }}</p>
<ul class="reward-card__list">
    <li class="preview-card__list-items">
        <span class="icon"><i class="las la-check"></i></span>
        <p class="text one">{{ __($level->rewards->one) }}</p>
    </li>
    <li class="preview-card__list-items">
        <span class="icon"><i class="las la-check"></i></span>
        <p class="text two">{{ __($level->rewards->two) }}</p>
    </li>
    <li class="preview-card__list-items">
        <span class="icon"><i class="las la-check"></i></span>
        <p class="text three">{{ __($level->rewards->three) }}</p>
    </li>
</ul>
