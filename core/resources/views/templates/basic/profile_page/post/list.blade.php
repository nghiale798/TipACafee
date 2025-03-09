@foreach ($posts as $post)
    @php
        $coffee = @$user->donationSetting->donation_price ?? $general->starting_price;
        $authUser = auth()->user();
        $support = $authUser?->supporter->where('status', Status::ENABLE)->count();
        $member = $authUser?->myMemberships()->where('status', Status::ENABLE)->whereHas('level', function ($query) {
                $query->where('status', Status::ENABLE);
            })->count();
        $mySupporter = $post->visible == Status::VISIBLE_SUPPORTER && $support;
        $myMember = $post->visible == Status::VISIBLE_MEMBER && $member;
    @endphp
    <div class="content-list">
        <div class="post">
            <div class="post__content">
                <span class="post__date">{{ showDateTime($post->created_at, 'M d, Y') }}</span>
                <h4 class="post__title"><a href="{{ route('post.view', [$user->profile_link, $post->slug]) }}">
                        {{ __(strLimit($post->title, 80)) }}</a></h4>

                @if ($post->visible == Status::VISIBLE_PUBLIC || $myMember || $mySupporter || $user->id == @$authUser?->id)
                    <p class="post__desc"> @php echo __(strLimit(strip_tags($post->content), 150));@endphp</p>
                @elseif ($post->visible == Status::VISIBLE_MEMBER && !$myMember)
                    <div class="visible-member">
                        <span class="icon"><img src="{{ getImage($activeTemplateTrue . 'images/icons/member-lock.png') }}" alt=""></span>
                        <p class="title mb-0">@lang('Members only')</p>
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

            </div>

            <div class="post__footer flex-between">
                <span class="react flex-align">
                    @auth
                        <span class="icon like-btn" data-post_id ="{{ $post->id }}">
                            @if ($post->isLikedBy(auth()->user()))
                                <i class="fas fa-heart icf"></i>
                            @else
                                <i class="far fa-heart"></i>
                            @endif
                        </span>
                    @else
                        <span class="icon">
                            <a class="user-login" href="{{ route('user.login') }}" target="_blank">
                                <i class="far fa-heart"></i>
                            </a>
                        </span>
                    @endauth
                    <span class="count like-count d-inline">
                        @if ($post->likes_count)
                            {{ shortNumber($post->likes_count) }}
                        @endif
                    </span>

                    @if ($post->comments_count)
                        <span class="icon comment">
                            @auth
                                @if ($post->isCommentedBy(auth()->user()))
                                    <i class="fas fa-comment-alt icf"></i>
                                @else
                                    <i class="far fa-comment-alt"></i>
                                @endif
                            @else
                                <i class="far fa-comment-alt"></i>
                            @endauth
                            <span class="count d-inline"> {{ shortNumber($post->comments_count) }}</span>
                        </span>
                    @endif
                </span>
            </div>

        </div>
    </div>
@endforeach
