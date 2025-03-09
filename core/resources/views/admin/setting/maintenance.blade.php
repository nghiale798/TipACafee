@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>@lang('Image')</label>
                                    <x-image-uploader class="w-100" type="maintenance" image="{{ @$maintenance->data_values->image }}" :required="$maintenance->data_values->image ? false : true" />
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>@lang('Status')</label>
                                    <input name="status" data-width="100%" data-height="50" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disabled')" type="checkbox" @if (@$general->maintenance_mode) checked @endif>
                                </div>

                                <div class="form-group">
                                    <label>@lang('Heading')</label>
                                    <input class="form-control" name="heading" type="text" value="{{ @$maintenance->data_values->heading }}" required>
                                </div>

                                <div class="form-group">
                                    <label>@lang('Description')</label>
                                    <textarea class="form-control nicEdit" name="description" rows="10">@php echo @$maintenance->data_values->description @endphp</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
