@extends($activeTemplate . 'layouts.' . $layout)

@section('content')
    <div class="@if ($layout == 'frontend') account pt-3 @else multi-page-card @endif ">
        <div class="@if ($layout == 'frontend') account-inner @else multi-page-card__body @endif ">
            <div class="container ">
                <div class="row justify-content-center @if ($layout == 'frontend') account-form @endif">
                    <div class="col-md-12">
                        <div class="d-flex flex-wrap justify-content-between align-items-center">
                            <h5 class="flex-align">
                                @php echo $myTicket->statusBadge; @endphp
                                [@lang('Ticket')#{{ $myTicket->ticket }}] {{ $myTicket->subject }}
                            </h5>
                            <div class="d-flex">
                                @if ($myTicket->status != Status::TICKET_CLOSE && $myTicket->user)
                                    <button class="btn btn--danger close-button btn--sm confirmationAltBtn mb-3" data-question="@lang('Are you sure to close this ticket?')" data-action="{{ route('ticket.close', $myTicket->id) }}" type="button"><i class="las la-times"></i></button>
                                @endif
                            </div>
                        </div>
                        <form method="post" action="{{ route('ticket.reply', $myTicket->id) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row justify-content-between">
                                <div class="col-md-12 form-group">
                                    <div class="form--group">
                                        <textarea class="form--control" name="message" rows="4">{{ old('message') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn--base btn--sm addFile ms-auto" type="button"><i class="fa fa-plus"></i> @lang('Add New')</button>
                            <div class="form-group">
                                <label class="form-label">@lang('Attachments')</label> <small class="text--danger">@lang('Max 5 files can be uploaded'). @lang('Maximum upload size is') {{ ini_get('upload_max_filesize') }}</small>
                                <input class="form--control" name="attachments[]" type="file" />
                                <div id="fileUploadsContainer"></div>
                                <small class="my-2 ticket-attachments-message text-muted">
                                    @lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'), .@lang('pdf'), .@lang('doc'), .@lang('docx')
                                </small>
                            </div>
                            <button class="btn btn--base w-100" type="submit"> <i class="fa fa-reply"></i> @lang('Reply')</button>
                        </form>
                        @foreach ($messages as $message)
                            @if ($message->admin_id == 0)
                                <div class="row border border-primary border-radius-3 my-3 py-3 mx-2">
                                    <div class="col-md-3 border-end text-md-end text-start">
                                        <h5 class="my-3">{{ $message->ticket->name }}</h5>
                                    </div>
                                    <div class="col-md-9">
                                        <small class="fst-italic my-2">
                                            @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ h:i A') }}</small>
                                        <p>{{ $message->message }}</p>
                                        @if ($message->attachments->count() > 0)
                                            <div class="mt-2">
                                                @foreach ($message->attachments as $k => $image)
                                                    <a class="me-2" href="{{ route('ticket.download', encrypt($image->id)) }}"><i class="fa fa-file"></i> @lang('Attachment') {{ ++$k }} </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="row border border-warning border-radius-3 my-3 py-3 mx-2" style="background-color: #ffd96729">
                                    <div class="col-md-3 border-end text-end">
                                        <h5 class="my-3">{{ $message->admin->name }}</h5>
                                        <p class="lead text-muted">@lang('Staff')</p>
                                    </div>
                                    <div class="col-md-9">
                                        <p class="text-muted fw-bold my-3">
                                            @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</p>
                                        <p>{{ $message->message }}</p>
                                        @if ($message->attachments->count() > 0)
                                            <div class="mt-2">
                                                @foreach ($message->attachments as $k => $image)
                                                    <a class="me-3" href="{{ route('ticket.download', encrypt($image->id)) }}"><i class="fa fa-file"></i> @lang('Attachment') {{ ++$k }} </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-confirmation-alert />

@endsection
@push('style')
    <style>
        
        .input-group-text:focus {
            box-shadow: none !important;
        }

        @media screen and (max-width: 767px) {
            .template .template-body {
                padding: 0px 0px;
            }

            .multi-page-card__body {
                padding-top: 5px;
            }

            .multi-page-card {
                background-color: unset !important;
            }
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
                        <button type="submit" class="input-group-text btn-danger remove-btn"><i class="las la-times"></i></button>
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
