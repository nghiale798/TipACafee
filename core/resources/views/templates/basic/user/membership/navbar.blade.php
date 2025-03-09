@if(auth()->user()->is_enable_membership)
<div class="multi-page-card__header">
    <ul class="multi-page-card__link-list">
        <li class="multi-page-card__link {{ menuActive('user.membership.index') }}"><a href="{{ route('user.membership.index') }}">@lang('Membership')</a></li>
        <li class="multi-page-card__link {{ menuActive('user.membership.level') }}"><a href="{{ route('user.membership.level') }}">@lang('Level')</a></li>
        <li class="multi-page-card__link {{ menuActive('user.membership.setting') }}"><a href="{{ route('user.membership.setting') }}">@lang('Setting')</a></li>
        <li class="multi-page-card__link {{ menuActive('user.membership.my.membership') }}"><a href="{{ route('user.membership.my.membership') }}">@lang('My Membership')</a></li>
    </ul>
</div>
@endif
