<div class="tab-content-list pt-2">
    <div class="post-list-card gallery-card">
        <div class="gallery-card__thumb">
            <img alt="" src="{{ getImage(getFilePath('gallery') . '/' . $gallery->image, getFileSize('gallery')) }}" loading="lazy">
        </div>
        <div class="gallery-card__body">
            <div class="post-list-card__header flex-between">
                <p class="post-list-card__time flex-align">
                    <span class="text"> @lang('Posted at') <span class="date-br"> {{ showDateTime($gallery->created_at, 'M d, Y h:i A') }}
                        </span>
                    </span>
                    @if ($gallery->is_pinned)
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
                            @if ($gallery->status == Status::PUBLISH)
                                <li class="action-list">
                                    <a class="action-list_btn" href="{{ route('gallery.view', [$gallery->user->profile_link, $gallery->slug, $gallery->id]) }}" target="_blank"><span>@lang('View photo')</span>
                                    </a>
                                </li>
                            @endif
                            <li class="action-list">
                                @if ($gallery->status == Status::PUBLISH)
                                    <button class="action-list_btn" class="confirmationAltBtn" data-action="{{ route('user.gallery.status', $gallery->id) }}" data-question="@lang('Are you sure to draft it?')" type="button" type="button"><span>@lang('Draft')</span>
                                    </button>
                                @else
                                    <button class="action-list_btn" class="confirmationAltBtn" data-action="{{ route('user.gallery.status', $gallery->id) }}" data-question="@lang('Are you sure to publish it?')" type="button" type="button"><span>@lang('Publish')</span>
                                    </button>
                                @endif
                            </li>
                            <li class="action-list"><button class="action-list_btn cursor-pointer edit-photo-btn" data-photo="{{ getImage(getFilePath('gallery') . '/' . $gallery->image, getFileSize('gallery')) }}" data-resource="{{ $gallery }}" type="button">@lang('Edit')</button></li>
                            <li class="action-list"><button class="action-list_btn text--danger cursor-pointer confirmationAltBtn" data-question="@lang('Are you sure to delete this galley image?')" data-action="{{ route('user.gallery.delete', $gallery->id) }}" type="button">@lang('Delete')</button></li>
                            <li class="action-list">
                                <a class="action-list_btn" href="{{ route('user.gallery.pin.unpinned', $gallery->id) }}">
                                    @if (@$gallery->is_pinned)
                                        @lang('Unpin')
                                    @else
                                        @lang('Pin this post')
                                    @endif
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <h4 class="post-list-card__title">{{ __(strLimit($gallery->title, 90)) }}</h4>
            <div class="post-list-card__footer flex-between">
                <div class="post-list-card__status flex-align">
                    @if (@$gallery->visible == Status::VISIBLE_PUBLIC)
                        <span class="icon">
                            <i class="fas fa-globe"></i>
                        </span>
                        <p class="type">@lang('Public')</p>
                    @elseif(@$gallery->visible == Status::VISIBLE_SUPPORTER)
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
                    <span class="like">{{ $gallery->likes_count }} @lang('like')</span>
                    <span class="Comment">{{ $gallery->comments_count }} @lang('Comment')</span>
                </div>
            </div>
        </div>
    </div>
</div>
