<div class="comment-list @if (@!$skeleton) skeleton @endif">
    <div class="comment-text flex-align">
        <div class="profile-info flex-align gap-3">
            <span class="profile-thumb">
                <img src="{{ avatar(@$comment->user->image ? getFilePath('userProfile') . '/' . $comment->user->image : null) }}" alt="">
            </span>
            <div class="profile-content flex-align gap-2">
                <h6 class="profile-name">{{ __(@$comment->user->fullname) }}</h6>
                <span class="comment-time">{{ showDateTime($comment->updated_at, 'F d, Y') }}</span>
            </div>
        </div>
        @if ($comment->user->id == auth()->id())
            <div class="dropdown">
                <span class="icon" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                    <i class="fas fa-ellipsis-h"></i>
                </span>
                <ul class="dropdown-menu post-list-card__action p-2 py-3">
                    <li class="action-list editMode cursor-pointer mb-2"><span>@lang('Edit')</span></li>
                    <li class="action-list text--danger confirmationAltBtn cursor-pointer" data-question="@lang('Are you sure to delete this comment?')" data-action="{{ route('user.post.comment.delete', $comment->id) }}"><span>@lang('Delete')</span> </li>
                </ul>
            </div>
        @endif
    </div>
    <p class="post-comment-text">{{ __($comment->comment) }}</p>
    @if ($comment->user_id == auth()->id())
        <form class="send__comment comment-edit-wrapper commentEditForm d-none">
            <input name="comment_id" type="hidden" value="{{ $comment->id }}">
            <div class="input-group">
                <textarea class="form--control post-comment edit-comment" name="comment" type="text">{{ __($comment->comment) }} </textarea>
                <button class="btn--base btn-sm comment-edit-send-btn" type="submit"><i class="las la-paper-plane"></i></button>
            </div>
        </form>
    @endif
</div>
