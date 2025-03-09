<div class="tab-content-list">
    <div class="post-list-card">
        <div class="post-list-card__header flex-between">
            <p class="post-list-card__time flex-align">
                <span class="text"> @lang('Posted at') <span class="date-br"> {{ showDateTime($post->created_at, 'M d, Y h:i A') }}</span></span>
                @if ($post->is_pinned)
                    <span class="is-pinned">
                        <span class="icon"><i class="fas fa-thumbtack"></i></span>
                        <span class="text">@lang('PINNED')</span>
                    </span>
                @endif
            </p>
            <div class="post-list-card__option">
                <div class="dropdown">
                    <span class="icon" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                        <i class="fas fa-ellipsis-h"></i>
                    </span>
                    <ul class="dropdown-menu post-list-card__action">
                        @if ($post->status == Status::PUBLISH)
                            <li class="action-list">
                                <a class="action-list_btn" href="{{ route('post.view', [$post->user->profile_link, $post->slug]) }}" target="_blank"><span>@lang('View post')</span>
                                </a>
                            </li>
                        @endif
                        <li class="action-list">
                            <a class="action-list_btn" href="{{ route('user.post.edit', $post->id) }}"> <span>@lang('Edit')</span></a>
                        </li>
                        <li class="action-list">
                            <a class="action-list_btn" href="{{ route('user.post.pin.unpinned', $post->id) }}">
                                <span>
                                    @if (@$post->is_pinned)
                                        @lang('Unpin')
                                    @else
                                        @lang('Pin this post')
                                    @endif
                                </span>
                            </a>
                        </li>
                        <li class="action-list ">
                            <button class="action-list_btn text-start w-100 text--danger cursor-pointer confirmationAltBtn" data-question="@lang('Are you sure to delete this post?')" data-action="{{ route('user.post.delete', $post->id) }}" type="button">
                                @lang('Delete')
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <h4 class="post-list-card__title"><a href="{{ route('user.post.edit', $post->id) }}">{{ __(strLimit($post->title, 90)) }}</a></h4>

        <div class="post-list-card__footer flex-between">
            <div class="post-list-card__status flex-align">
                @if (@$post->visible == Status::VISIBLE_PUBLIC)
                    <span class="icon">
                        <i class="fas fa-globe"></i>
                    </span>
                    <p class="type">@lang('Public')</p>
                @elseif(@$post->visible == Status::VISIBLE_SUPPORTER)
                    <span class="icon">
                        <i class="fa fa-heart"></i>
                    </span>
                    <p class="type">@lang('Supporters Only')</p>
                @else
                    <span class="icon">
                        <i class="fa fa-lock"></i>
                    </span>
                    <p class="type">@lang('Members Only')</p>
                @endif
            </div>
            <div class="post-list-card__react flex-align">
                <span class="like">{{ $post->likes_count }} @lang('like')</span>
                <span class="Comment">{{ $post->comments_count }} @lang('Comment')</span>
            </div>
        </div>
    </div>
</div>
