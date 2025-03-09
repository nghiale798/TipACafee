<div class="multi-page-card__header">
    <ul class="multi-page-card__link-list">
        <li class="multi-page-card__link {{ menuActive('user.explore.index') }}"><a href="{{ route('user.explore.index') }}">@lang('Explore Creators')</a></li>
        <li class="multi-page-card__link {{ menuActive('user.explore.followers') }}"><a href="{{ route('user.explore.followers') }}">@lang('Followers')</a></li>
        <li class="multi-page-card__link {{ menuActive('user.explore.following') }}"><a href="{{ route('user.explore.following') }}">@lang('Following')</a></li>
    </ul>
</div>
