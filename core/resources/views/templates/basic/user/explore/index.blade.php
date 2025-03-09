@extends($activeTemplate . 'layouts.master')
@section('content')
    <h2 class="page-title">{{ __($pageTitle) }}</h2>
    <div class="multi-page-card">
        @include($activeTemplate . 'user.explore.navbar')
        <div class="multi-page-card__body">
            <form class="search creator-search" method="GET">
                <div class="input-group mb-3">
                    <input class="form-control form--control creator-search-field" id="searchInput" name="search" type="search" autocomplete="off" placeholder="@lang('Search here 10,000,000+ creators')">
                    <span class="search-icon">
                        <i class="las la-search"></i>
                    </span>
                </div>
                <ul class="search-list"></ul>
            </form>

            <h5>@lang('Trending creators this week')</h5>
            <div class="row gy-4">
                @foreach ($trendingCreators as $user)
                    <div class="col-sm-6">
                        <div class="explore-creator">
                            <a class="explore-creator__link" href="{{ route('home.page', $user->profile_link) }}"></a>
                            <div class="explore-creator__thumb">
                                <img src="{{ avatar($user->image ? getFilePath('userProfile') . '/' . $user->image : null) }}" alt="creators">
                            </div>
                            <div class="explore-creator__content">
                                <h6 class="auhtor-name">{{ __($user->fullname) }}</h6>
                                <p>{{ __(@$user->creation) }}</p>
                                @if ($user->show_supporter_count)
                                    <span class="supporter">
                                        <i class="far fa-heart"></i> {{ shortNumber(count($user->donations)) }} @lang('Supporters')
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @if (!blank($users))
        <div class="published-feed-heading my-4 d-flex">
            <h2 class="page-title mb-0 position-relative">@lang('Publish Now')
                <span class="pulsating-circle"></span>
            </h2>
        </div>
        @foreach ($users as $user)
            <div class="published-feed-item">
                <div class="published-feed-item__header">
                    <div class="published-feed-profile">
                        <div class="thumb">
                            <a href="{{ route('home.page', $user->profile_link) }}" target="_blank"> <img src="{{ avatar($user->image ? getFilePath('userProfile') . '/' . $user->image : null) }}" alt="img"></a>
                        </div>
                        <div class="content">
                            <a href="{{ route('home.page', $user->profile_link) }}" target="_blank">{{ @$user->fullname }}</a> <span>@lang('added a new post').</span>
                        </div>
                    </div>
                    <div class="published-feed-profile-follow">
                        @php
                            $isFollowing = App\Models\Following::where('user_id', $user->id)
                                ->where('follower_id', auth()->id())
                                ->first();
                        @endphp

                        <button class="btn follow-button--sm btn--outline follow-button follow" data-user_id= "{{ @$user->id }}" type="button">
                            @if (@$isFollowing)
                                <span class="icon"><i class="las la-check"></i></span> <span class="follow-text">@lang('Following')</span>
                            @else
                                <span class="icon"><i class="las la-plus"></i></span> <span class="follow-text">@lang('Follow')</span>
                            @endif
                        </button>
                    </div>
                </div>
                <div class="row gy-4">
                    @foreach ($user->latestPosts as $post)
                        @php
                            $authUser = auth()->user();
                            $support = $authUser?->supporter->where('status', Status::ENABLE)->count();
                            $member = $authUser
                                ?->myMemberships()
                                ->where('status', Status::ENABLE)
                                ->whereHas('level', function ($query) {
                                    $query->where('status', Status::ENABLE);
                                })
                                ->count();
                            $mySupporter = $post->visible == Status::VISIBLE_SUPPORTER && $support;
                            $myMember = $post->visible == Status::VISIBLE_MEMBER && $member;
                        @endphp
                        <div class="col-md-6">
                            <div class="published-feed-post lock-post">
                                @if ($post->image)
                                    @if ($post->visible == Status::VISIBLE_PUBLIC || $myMember || $mySupporter || $user->id == @$authUser?->id)
                                        <div class="thumb">
                                            <img src="{{ getImage(getFilePath('gallery') . '/' . $post->image, getFileSize('gallery')) }}" alt="" loading="lazy">
                                        </div>
                                    @elseif ($post->visible == Status::VISIBLE_MEMBER && !$myMember)
                                        <div class="visible-member mb-2">
                                            <a href="{{ route('gallery.view', [$user->profile_link, $post->slug, $post->id]) }}">
                                                <span class="icon"><img src="{{ getImage($activeTemplateTrue . 'images/icons/member-lock.png') }}" alt=""></span>
                                                <span class="title pt-2">@lang('Members only')</span>
                                            </a>
                                        </div>
                                    @elseif ($post->visible == Status::VISIBLE_SUPPORTER && !$mySupporter)
                                        <div class="visible-member visible-supporter">
                                            <a href="{{ route('gallery.view', [$user->profile_link, $post->slug, $post->id]) }}">
                                                <span class="icon"><img src="{{ getImage($activeTemplateTrue . 'images/icons/supporter-lock.png') }}" alt=""></span>
                                                <span class="title pt-2">@lang('Supporters only')</span>
                                            </a>
                                        </div>
                                    @endif
                                @else
                                    @if ($post->visible == Status::VISIBLE_PUBLIC || $myMember || $mySupporter || $user->id == @$authUser?->id)
                                        <div class="feed-post-content">
                                            <p>@php echo __(strip_tags($post->content));@endphp</p>
                                        </div>
                                    @elseif ($post->visible == Status::VISIBLE_MEMBER && !$myMember)
                                        <div class="visible-member mb-2">
                                            <span class="icon">
                                                <img src="{{ getImage($activeTemplateTrue . 'images/icons/member-lock.png') }}" alt="">
                                            </span>
                                            <p class="title pt-2">@lang('Members only')</p>
                                            <a class="btn join-btn btn--outline" href="{{ route('post.view', [$user->username, $post->slug]) }}">@lang('Join as member')</a>
                                            @if (!auth()->check())
                                                <span class="member-login">@lang('Already a member?')<a href="{{ route('user.login') }}">@lang('Sign in')</a></span>
                                            @endif
                                        </div>
                                    @elseif ($post->visible == Status::VISIBLE_SUPPORTER && !$mySupporter)
                                        <div class="visible-member visible-supporter">
                                            <a href="{{ route('post.view', [$user->username, $post->slug]) }}">
                                                <span class="icon"><img src="{{ getImage($activeTemplateTrue . 'images/icons/supporter-lock.png') }}" alt=""></span>
                                                <p class="title pt-2">@lang('Supporters only')</p>
                                                <span class="btn join-btn btn--outline supportBtn @disabled(checkOwner($user->id))">@lang('Support') {{ $general->cur_sym }}{{ getAmount(@$coffee) }}</span>
                                            </a>
                                        </div>
                                    @endif
                                @endif
                                <div class="content mt-2">
                                    <h6 class="title mb-1">
                                        {{ __(strLimit($post->title, 55)) }}
                                    </h6>
                                    <span class="date fs-14"><i class="las la-calendar"></i> {{ showDateTime($post->created_at, 'F d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        @if ($users->hasPages())
            <div class="mt-3">
                {{ paginateLinks($users) }}
            </div>
        @endif
    @endif
@endsection

@push('script')
    <script>
        'use strict';

        $(document).ready(function() {
            var searchField = $('.creator-search-field');
            var searchResultPane = $('.search-list');
            var baseProfileUrl = "{{ route('home.page', ':link') }}";
            var nextPageUrl = null; // Store the next page URL

            searchField.on('input', function() {
                var search = $(this).val().toLowerCase();
                if (search.length == 0) {
                    searchResultPane.addClass('d-none').html('');
                    nextPageUrl = null;
                    return;
                }

                // Clear the pane for a new search query
                searchResultPane.html('');
                nextPageUrl = null;
                fetchData("{{ route('user.explore.creators') }}", {
                    search: search
                });
            });


            $('.search-list').on('scroll', function() {
                var st = $(this).scrollTop();
                var sh = this.scrollHeight;
                var ch = $(this).height();

                // Check if scrolled to the bottom, and if nextPageUrl is not null
                if (st + ch >= sh - 100 && nextPageUrl) {
                    fetchData(nextPageUrl, {
                        search: $('.creator-search-field').val().toLowerCase(),
                        scroll_type: 'down',
                        id: $('.search-list li:last-child').data('id')
                    });
                    nextPageUrl = null;
                }
            });


            function fetchData(url, data = {}) {
                if (!url) return;

                $.ajax({
                    type: "GET",
                    url: url,
                    data: data,
                    success: function(response) {
                        if (!response.creators || response.creators.length === 0) {
                            if (searchResultPane.children().length === 0) {
                                searchResultPane.append('<li class="text-muted p-2">@lang('No creator found')</li>');
                            }
                        } else {
                            response.creators.forEach(function(creator) {
                                var profileUrl = baseProfileUrl.replace(':link', creator.profile_link);
                                searchResultPane.append(`
                        <li class="search-item-inner" data-id="${creator.id}">
                            <a target="_blank" href="${profileUrl}" class="search-item">
                                <span class="search-item-image">
                                    <img class="fit-image" src="${creator.image_path}" alt="creator">    
                                </span>
                                <div class="search-item-content">
                                    <h6 class="search-item-name">${creator.firstname} ${creator.lastname}</h6>
                                    ${creator.creation ? `<span class="search-item-creation">${creator.creation}</span>` : ''}
                                </div>
                            </a>
                        </li>
                    `);
                            });
                        }
                        nextPageUrl = response.nextPageUrl; // Update the nextPageUrl only if available
                        searchResultPane.removeClass('d-none');
                    },
                    error: function() {
                        searchResultPane.html('<li class="text-danger pl-5">Error fetching results.</li>').removeClass('d-none');
                    }
                });
            }


            searchField.on('keydown', function(e) {
                let items = searchResultPane.find('li');
                let current = items.filter('.active').index();
                let next;

                if (e.keyCode === 40) { // Down arrow
                    next = (current + 1) % items.length; // loop around
                } else if (e.keyCode === 38) { // Up arrow
                    next = current - 1 < 0 ? items.length - 1 : current - 1; // loop around
                } else if (e.keyCode === 13) { // Enter key
                    var activeItem = searchResultPane.find('li.active a');
                    if (activeItem.length) {
                        window.location.href = activeItem.attr('href');
                        e.preventDefault();
                        return;
                    }
                }

                items.removeClass('active');
                if (typeof next !== 'undefined') {
                    items.eq(next).addClass('active');
                    e.preventDefault();
                }
            });



            $('.follow-button').on('click', function(e) {
                e.preventDefault();
                var isAuthenticated = @json(auth()->check());
                if (!isAuthenticated) {
                    notify('error', 'Sign in required for following');
                    return;
                }
                var $this = $(this);
                var userId = $this.data('user_id');
                var followerId = `{{ auth()->id() }}`;
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
                            $this.html('<i class="las la-check"></i> <span class="follow-text">@lang('Following')</span>');
                        } else {
                            $this.html('<i class="las la-plus"></i> <span class="follow-text">@lang('Unfollow')</span>');
                            if (response.action === 'unfollow') {
                                $this.html('<i class="las la-plus"></i> <span class="follow-text">@lang('Follow')</span>');
                            }
                        }
                    }
                });
            });

            $('.follow-button').hover(
                function() { // Mouse enter
                    var $followText = $(this).find('.follow-text');
                    if ($followText.text() === '@lang('Following')') {
                        $followText.text('@lang('Unfollow')');
                    }
                    if ($followText.text() === '@lang('Follow')') {
                        $followText.text('@lang('Follow')');
                    }
                },
                function() { // Mouse leave
                    var $followText = $(this).find('.follow-text');
                    if ($followText.text() === '@lang('Unfollow')') {
                        $followText.text('@lang('Following')');
                    }
                }
            );
        });
    </script>
@endpush
