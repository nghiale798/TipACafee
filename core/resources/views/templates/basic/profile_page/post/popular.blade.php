<div class="post-list-card">
    <h5 class="post-list-card__title">@lang('POPULAR')</h5>
    <ul class="post-list-card__list">
        @foreach ($popularPosts as $post)
            <li class="post-list-card__item">
                <a class="post-list-card__link" href="{{ route('post.view', [$user->profile_link, $post->slug]) }}">
                    <p class="title">{{ __($post->title) }}</p>
                    <p class="react flex-align gap-2">
                        @if ($post->likes_count)
                            <span class="icon">
                                @if (auth()->check())
                                    @if ($post->isLikedBy(auth()->user()))
                                        <i class="fas fa-heart icf"></i>
                                    @else
                                        <i class="far fa-heart"></i>
                                    @endif
                                @else
                                    <i class="far fa-heart"></i>
                                @endif
                                <span class="count  d-inline">{{ $post->likes_count }}</span>
                            </span>
                        @endif
                        @if ($post->comments_count)
                            <span class="icon">
                                @if (auth()->check())
                                    @if ($post->isCommentedBy(auth()->user()))
                                        <i class="fas fa-comment-alt icf"></i>
                                    @else
                                        <i class="far fa-comment-alt"></i>
                                    @endif
                                @else
                                    <i class="far fa-comment-alt"></i>
                                @endif
                                <span class="count  d-inline">{{ $post->comments_count }}</span>
                            </span>
                        @endif
                    </p>
                </a>
            </li>
        @endforeach
    </ul>
</div>
