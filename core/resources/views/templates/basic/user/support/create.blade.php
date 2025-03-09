@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="multi-page-card">
        <div class="multi-page-card__body">
            <div class="row justify-content-center gy-4">
                <div class="col-md-12">
                    <div class="ticket-header d-flex flex-wrap align-items-center justify-content-between mb-5">
                        <h5 class="mb-0">{{ __($pageTitle) }}</h5>
                        <a class="btn btn--sm btn--base" href="{{ route('ticket.index') }}">@lang('My Support Ticket')</a>
                    </div>

                    <form action="{{ route('ticket.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form--group col-md-6">
                                <label class="form-label">@lang('Subject')</label>
                                <input class="form--control" name="subject" type="text" value="{{ old('subject') }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form--label">@lang('Priority')</label>
                                <select class="form-select form--control" name="priority" required>
                                    <option value="3">@lang('High')</option>
                                    <option value="2">@lang('Medium')</option>
                                    <option value="1">@lang('Low')</option>
                                </select>
                            </div>
                            <div class="col-12 form-group">
                                <label class="form--label">@lang('Message')</label>
                                <textarea class="form--control" id="inputMessage" name="message" rows="6" required>{{ old('message') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="text-end">
                                <button class="btn btn--base btn--sm ms-auto addFile" type="button">
                                    <i class="fa fa-plus"></i> @lang('Add New')
                                </button>
                            </div>
                            <div class="file-upload">
                                <label class="form--label">@lang('Attachments')</label> <small class="text--danger">@lang('Max 5 files can be uploaded'). @lang('Maximum upload size is') {{ ini_get('upload_max_filesize') }}</small>
                                <input class="form--control mb-2" id="inputAttachments" name="attachments[]" type="file" />
                                <div id="fileUploadsContainer"></div>
                                <p class="ticket-attachments-message text-muted">
                                    @lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'), .@lang('pdf'), .@lang('doc'), .@lang('docx')
                                </p>
                            </div>

                        </div>

                        <div class="form-group">
                            <button class="btn btn--base w-100 ms-auto" type="submit"><i class="fa fa-paper-plane"></i>&nbsp;@lang('Submit')</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .input-group-text:focus {
            box-shadow: none !important;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            var fileAdded = 0;
            $('.addFile').on('click', function() {
                if (fileAdded >= 4) {
                    notify('error', 'You\'ve added maximum number of file');
                    return false;
                }
                fileAdded++;
                $("#fileUploadsContainer").append(`
                    <div class="input-group my-3">
                        <input type="file" name="attachments[]" class="form-control form--control" required />
                        <button type="button" class="input-group-text btn-danger remove-btn"><i class="las la-times"></i></button>
                    </div>
                `)
            });
            $(document).on('click', '.remove-btn', function() {
                fileAdded--;
                $(this).closest('.input-group').remove();
            });
        })(jQuery);
    </script>
@endpush
