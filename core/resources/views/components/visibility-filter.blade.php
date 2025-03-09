<div class="w-auto flex-fill">
    <div class="input-group">

        <select class="select-filter form-control" name="visible">
            <option value="">@lang('All Visible')</option>
            <option value="1" @selected(request()->visible == 1)>@lang('Public')</option>
            <option value="2" @selected(request()->visible == 2)>@lang('Supporter')</option>
            <option value="3" @selected(request()->visible == 3)>@lang('Member')</option>
        </select>
        <button type="submit" class="btn btn--primary input-group-text"><i class="la la-search"></i></button>
    </div>
</div>
