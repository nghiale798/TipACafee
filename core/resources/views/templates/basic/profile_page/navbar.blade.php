<div class="mb-4">
    <h4 class="profile-name text-center">{{ __($user->fullname) }}</h4>
    <div class="text-center pb-1">
        @if ($user->show_supporter_count)
            <span class="fs-14"><i class="lar la-heart"></i> {{ shortNumber(count($user->donations)) }} @lang('Supporters') </span>
        @endif
        <span class="fs-14"><i class="las la-user-check"></i> <span class="totalFollower">{{ shortNumber(count($user->followers)) }}</span> @lang('Followers') </span>
    </div>
    <p class="profile-creation fw-bold text-center">{{ __($user->creation) }}</p>
</div>

<ul class="profile-menu">
    <li class="profile-menu__item {{ menuActive('home.page*') }}">
        <a class="profile-menu__link " href="{{ route('home.page', $user->profile_link) }}">@lang('Home')</a>
    </li>

    @if ($user->is_enable_membership && count($membershipLevel))
        <li class="profile-menu__item {{ menuActive(['membership.page*']) }}">
            <a class="profile-menu__link" href="{{ route('membership.page', $user->profile_link) }}">@lang('Membership')</a>
        </li>
    @endif
    @if (@$posts)
        <li class="profile-menu__item {{ menuActive(['post.page*', 'post.view*']) }}">
            <a class="profile-menu__link " href="{{ route('post.page', $user->profile_link) }}">@lang('Post')</a>
        </li>
    @endif
    @if (@$galleryImages)
        <li class="profile-menu__item {{ menuActive(['gallery.page*', 'gallery.view*']) }}">
            <a class="profile-menu__link" href="{{ route('gallery.page', $user->profile_link) }}">@lang('Gallery')</a>
        </li>
    @endif
</ul>
