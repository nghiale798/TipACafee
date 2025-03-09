<div class="w-auto flex-fill">
    <div class="input-group">
        <select class="select-filter form-control" name="status">
            <option value="">@lang('All Status')</option>
            <option value="0" @selected(request()->status != null && request()->status == 0)>@lang('Draft')</option>
            <option value="1" @selected(request()->status == 1)>@lang('Published')</option>
        </select>
        <button type="submit" class="btn btn--primary input-group-text"><i class="la la-search"></i></button>
    </div>
</div>
