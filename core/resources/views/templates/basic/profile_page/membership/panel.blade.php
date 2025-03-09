<div class="form-group">
    <h4 class="fs-20 fw-medium  package-name"></h4>
</div>
<div class="form-group">
    <p class="fs-14 text-start  package-description"></p>
</div>
<div class="form-group">
    <ul class="reward-card__list membership-modal">
        <li class="preview-card__list-items">
            <span class="icon"><i class="las la-check"></i></span>
            <p class="text one fs-14"></p>
        </li>
        <li class="preview-card__list-items">
            <span class="icon"><i class="las la-check"></i></span>
            <p class="text two fs-14"></p>
        </li>
        <li class="preview-card__list-items">
            <span class="icon"><i class="las la-check"></i></span>
            <p class="text three fs-14"></p>
        </li>
    </ul>
</div>
<form action="{{ route('payment.index') }}" method="POST">
    @csrf
    <div class="form-group">
        <textarea class="form--control" name="message" placeholder="@lang('Say something (optional)')">{{ old('message') }}</textarea>
    </div>
    <div class="form-group">
        <label class="privet-message form--check" for="payment-privet-message_member_{{ $id }}">
            <span class="custom--check">
                <input class="form-check-input" id="payment-privet-message_member_{{ $id }}" name="is_message_private" type="checkbox" />
            </span>
            <span class="form-check-label modal-check-label">@lang('Make this message private?')</span>
        </label>
    </div>

    <input name="user_id" type="hidden" value="{{ $user->id }}">
    <input name="amount" type="hidden">
    <input name="duration_type" type="hidden">
    <input name="membership_level_id" type="hidden">
    <button class="btn my-4 btn--outline btn--sm w-100  @if (!auth()->user() || auth()->id() == $user->id) disabled @endif" type="submit">@lang('Join Now')</button>
</form>

@push('style')
    <style>
        .form--check .modal-check-label {
            width: calc(100% - 208px);
        }
    </style>
@endpush
